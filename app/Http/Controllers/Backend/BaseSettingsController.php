<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

abstract class BaseSettingsController extends Controller
{
    protected $category;
    protected $model;
    protected $viewPath;
    protected $routePrefix;

    public function __construct()
    {
        $this->middleware('auth');
        $this->model = new Setting();
    }

    /**
     * Display a listing of settings
     */
    public function index(Request $request)
    {
        try {
            $query = $this->model->where('category', $this->category);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('key', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('value', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            // Handle AJAX requests for live search
            if ($request->ajax() && $request->has('ajax')) {
                $settings = $query->orderBy('sort_order')->orderBy('key')->get();
                
                // Generate suggestions (limit to 5)
                $suggestions = $settings->take(5)->map(function ($setting) {
                    return [
                        'id' => $setting->id,
                        'key' => $setting->key,
                        'description' => $setting->description,
                        'type' => $setting->type,
                        'type_icon' => $setting->type_icon ?? 'fas fa-cog',
                    ];
                });

                return response()->json([
                    'success' => true,
                    'settings' => $settings->map(function ($setting) {
                        return [
                            'id' => $setting->id,
                            'key' => $setting->key,
                            'description' => $setting->description,
                            'value' => $setting->value,
                            'formatted_value' => $setting->formatted_value,
                            'type' => $setting->type,
                            'type_icon' => $setting->type_icon ?? 'fas fa-cog',
                            'group_name' => $setting->group_name,
                            'is_active' => $setting->is_active,
                            'default_value' => $setting->default_value,
                        ];
                    }),
                    'suggestions' => $suggestions,
                ]);
            }

            // Get pagination setting or use default
            $paginationPerPage = \App\Models\Setting::get('default_pagination', 20, 'general');
            $settings_generals = $query->orderBy('sort_order')->orderBy('key')->paginate($paginationPerPage);

            if ($request->expectsJson()) {
                return response()->json($settings_generals);
            }

            return view("{$this->viewPath}.index", compact('settings_generals'));

        } catch (\Exception $e) {
            Log::error("Settings Controller Index Error ({$this->category}): " . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการโหลดข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified setting
     */
    public function show(Setting $setting)
    {
        if (request()->expectsJson()) {
            return response()->json($setting);
        }
        
        return view("{$this->viewPath}.show", compact('setting'));
    }


    /**
     * Update the specified setting
     */
    public function update(Request $request, Setting $setting)
    {
        // Debug logging at the start
        Log::info("=== UPDATE METHOD CALLED ===", [
            'setting_id' => $setting->id,
            'setting_key' => $setting->key,
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'has_file' => $request->hasFile('file'),
            'has_value' => $request->has('value'),
            'all_request_data' => $request->all()
        ]);

        try {
            DB::beginTransaction();

            $oldValue = $setting->value;
            
            // Debug logging
            Log::info("Updating setting", [
                'setting_id' => $setting->id,
                'key' => $setting->key,
                'has_file' => $request->hasFile('file'),
                'has_value' => $request->has('value'),
                'value' => $request->input('value'),
                'file_name' => $request->hasFile('file') ? $request->file('file')->getClientOriginalName() : null,
                'file_size' => $request->hasFile('file') ? $request->file('file')->getSize() : null,
                'request_data' => $request->except(['file']),
            ]);
            
            // Handle file upload for specific settings
            if (in_array($setting->key, ['site_logo', 'site_favicon'])) {
                // If request indicates removal, delete existing file and clear value
                if ($request->boolean('remove_file')) {
                    try {
                        if (!empty($oldValue)) {
                            // $oldValue is like 'settings/filename.ext' on public disk
                            Storage::disk('public')->delete($oldValue);
                        }
                    } catch (\Throwable $t) {
                        Log::warning('Failed to delete old setting file', [
                            'key' => $setting->key,
                            'path' => $oldValue,
                            'error' => $t->getMessage(),
                        ]);
                    }
                    // Clear value and allow updating other fields
                    $setting->value = '';
                    $request->validate([
                        'description' => 'nullable|string|max:255',
                        'is_active' => 'nullable|boolean'
                    ]);
                    $setting->description = $request->input('description', $setting->description);
                    $setting->is_active = $request->has('is_active') ? true : false;

                } elseif ($request->hasFile('file')) {
                    $file = $request->file('file');
                    
                    // Validate file and other fields (excluding value for file uploads)
                    $request->validate([
                        'file' => 'required|image|mimes:jpeg,png,gif,ico|max:2048',
                        'description' => 'nullable|string|max:255',
                        'is_active' => 'nullable|boolean'
                    ]);
                    
                    // Generate unique filename
                    $filename = $setting->key . '_' . time() . '.' . $file->getClientOriginalExtension();
                    
                    // Store file
                    $path = $file->storeAs('public/settings', $filename);
                    
                    // Update setting value with file path
                    $setting->value = 'settings/' . $filename;
                } else {
                    // No new file uploaded, update other fields only
                    $request->validate([
                        'description' => 'nullable|string|max:255',
                        'is_active' => 'nullable|boolean'
                    ]);
                    
                    $setting->description = $request->input('description', $setting->description);
                    $setting->is_active = $request->has('is_active') ? true : false;
                }
            } else {
                // Handle non-file settings
                $request->validate([
                    'value' => 'required',
                    'description' => 'nullable|string|max:255',
                    'is_active' => 'nullable|boolean'
                ]);
                
                $data = $request->all();
                $data['is_active'] = $request->has('is_active') ? true : false;
                
                $setting->fill($data);
                
                // Set typed value
                $this->setTypedValue($setting, $request->value);
            }
            
            $setting->updated_by = Auth::id();
            
            // Validate value (only for non-file settings and when value is provided)
            if (!in_array($setting->key, ['site_logo', 'site_favicon']) && $request->has('value')) {
                if (!$this->validateValue($setting, $request->value)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'errors' => ['value' => 'The value does not meet validation requirements.']
                        ], 422);
                    }
                    
                    return redirect()->back()
                        ->withErrors(['value' => 'The value does not meet validation requirements.'])
                        ->withInput();
                }
            }

            $setting->save();

            // Clear cache using SettingsService
            \App\Services\SettingsService::clearCache($setting->key);
            \App\Services\SettingsService::clearCache(); // Clear all category cache

            DB::commit();

            // Log activity
            Log::info("Setting updated ({$this->category})", [
                'id' => $setting->id,
                'key' => $setting->key,
                'old_value' => $oldValue,
                'new_value' => $setting->value,
                'user_id' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'อัปเดตการตั้งค่าเรียบร้อยแล้ว'
                ]);
            }

            return redirect()->route("{$this->routePrefix}.index")
                ->with('success', 'อัปเดตการตั้งค่าเรียบร้อยแล้ว');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            Log::error("Validation error updating setting", [
                'setting_id' => $setting->id,
                'key' => $setting->key,
                'errors' => $e->errors(),
                'request_data' => $request->except(['file']),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating setting ({$this->category})", [
                'error' => $e->getMessage(),
                'setting_id' => $setting->id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการอัปเดตการตั้งค่า: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการอัปเดตการตั้งค่า: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified setting
     */
    public function destroy(Setting $setting)
    {
        try {
            DB::beginTransaction();

            $settingKey = $setting->key;
            $setting->delete();

            // Clear cache
            $this->clearCache();

            DB::commit();

            // Log activity
            Log::info("Setting deleted ({$this->category})", [
                'id' => $setting->id,
                'key' => $settingKey,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route("{$this->routePrefix}.index")
                ->with('success', 'ลบการตั้งค่าเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting setting ({$this->category})", [
                'error' => $e->getMessage(),
                'setting_id' => $setting->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการลบการตั้งค่า: ' . $e->getMessage());
        }
    }

    /**
     * Toggle setting status
     */
    public function toggleStatus(Request $request, Setting $setting)
    {
        try {
            DB::beginTransaction();
            
            // Toggle both status and value for boolean settings
            $setting->is_active = !$setting->is_active;
            
            // For boolean settings, also toggle the value
            if ($setting->type === 'boolean') {
                $setting->value = $setting->is_active ? '1' : '0';
            }
            
            $setting->updated_by = Auth::id();
            $setting->save();
            
            // Clear cache
            \App\Services\SettingsService::clearCache($setting->key);
            \App\Services\SettingsService::clearCache(); // Clear all category cache
            
            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Setting status updated successfully',
                    'setting' => $setting->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'สถานะการตั้งค่าถูกอัปเดตเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error toggling setting status ({$this->category})", [
                'error' => $e->getMessage(),
                'setting_id' => $setting->id,
                'user_id' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:core_settings,id',
            'settings.*.value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $updatedCount = 0;
            foreach ($request->settings as $settingData) {
                $setting = Setting::find($settingData['id']);
                if ($setting && $setting->category === $this->category) {
                    $this->setTypedValue($setting, $settingData['value']);
                    
                    if ($this->validateValue($setting, $settingData['value'])) {
                        $setting->updated_by = Auth::id();
                        $setting->save();
                        $updatedCount++;
                    }
                }
            }

            // Clear cache
            $this->clearCache();

            DB::commit();

            // Log activity
            Log::info("Bulk settings update ({$this->category})", [
                'updated_count' => $updatedCount,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "อัปเดตการตั้งค่า {$updatedCount} รายการเรียบร้อยแล้ว",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in bulk settings update ({$this->category})", [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตการตั้งค่า'
            ], 500);
        }
    }

    /**
     * Reset setting to default value
     */
    public function reset(Setting $setting)
    {
        try {
            DB::beginTransaction();

            $oldValue = $setting->value;
            $setting->value = $setting->default_value;
            $setting->updated_by = Auth::id();
            $setting->save();

            // Clear cache
            $this->clearCache();

            DB::commit();

            // Log activity
            Log::info("Setting reset to default ({$this->category})", [
                'id' => $setting->id,
                'key' => $setting->key,
                'old_value' => $oldValue,
                'default_value' => $setting->default_value,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('success', 'รีเซ็ตการตั้งค่าเป็นค่าเริ่มต้นเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error resetting setting ({$this->category})", [
                'error' => $e->getMessage(),
                'setting_id' => $setting->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการรีเซ็ตการตั้งค่า: ' . $e->getMessage());
        }
    }

    /**
     * Export settings
     */
    public function export(Request $request)
    {
        $query = $this->model->where('category', $this->category);

        // Apply filters
        if ($request->filled('group')) {
            $query->where('group_name', $request->group);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $settings = $query->orderBy('sort_order')->orderBy('key')->get();

        $csvData = [];
        $csvData[] = ['Key', 'Value', 'Type', 'Group', 'Description', 'Active', 'Sort Order'];

        foreach ($settings as $setting) {
            $csvData[] = [
                $setting->key,
                $setting->value,
                $setting->type,
                $setting->group_name,
                $setting->description,
                $setting->is_active ? 'Yes' : 'No',
                $setting->sort_order,
            ];
        }

        $filename = "{$this->category}_settings_" . date('Y-m-d_H-i-s') . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get validation rules
     */
    protected function getValidationRules($excludeId = null)
    {
        $rules = [
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required'],
            'type' => ['required', 'string', 'in:string,boolean,integer,float,email,url,json,array'],
            'group_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_public' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'validation_rules' => ['nullable', 'array'],
            'default_value' => ['nullable'],
            'options' => ['nullable', 'array'],
        ];

        if ($excludeId) {
            $rules['key'][] = Rule::unique('core_settings')->ignore($excludeId);
        } else {
            $rules['key'][] = 'unique:core_settings';
        }

        return $rules;
    }

    /**
     * Get validation rules for update (excludes key, type, group_name)
     */
    protected function getUpdateValidationRules()
    {
        return [
            'value' => ['required'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_public' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'validation_rules' => ['nullable', 'array'],
            'default_value' => ['nullable'],
            'options' => ['nullable', 'array'],
        ];
    }

    /**
     * Get available groups for this category
     */
    protected function getAvailableGroups()
    {
        return $this->getCategoryGroups();
    }

    /**
     * Get available types
     */
    protected function getAvailableTypes()
    {
        return [
            'string' => 'String',
            'boolean' => 'Boolean',
            'integer' => 'Integer',
            'float' => 'Float',
            'email' => 'Email',
            'url' => 'URL',
            'json' => 'JSON',
            'array' => 'Array',
        ];
    }

    /**
     * Set typed value
     */
    protected function setTypedValue($setting, $value)
    {
        switch ($setting->type) {
            case 'boolean':
                $setting->value = $value ? '1' : '0';
                break;
            case 'integer':
                $setting->value = (string) (int) $value;
                break;
            case 'float':
                $setting->value = (string) (float) $value;
                break;
            case 'array':
            case 'json':
                $setting->value = json_encode($value);
                break;
            default:
                $setting->value = (string) $value;
        }
    }

    /**
     * Validate setting value
     */
    protected function validateValue($setting, $value)
    {
        if (empty($setting->validation_rules)) {
            return true;
        }

        $rules = $setting->validation_rules;
        
        // Basic validation
        if (isset($rules['required']) && $rules['required'] && empty($value)) {
            return false;
        }

        if (isset($rules['min']) && $value < $rules['min']) {
            return false;
        }

        if (isset($rules['max']) && $value > $rules['max']) {
            return false;
        }

        if (isset($rules['min_length']) && strlen($value) < $rules['min_length']) {
            return false;
        }

        if (isset($rules['max_length']) && strlen($value) > $rules['max_length']) {
            return false;
        }

        return true;
    }

    /**
     * Clear cache
     */
    protected function clearCache()
    {
        Cache::forget("settings_{$this->category}");
        Cache::forget('settings_all');
    }

    /**
     * Get category-specific groups (override in child classes)
     */
    protected function getCategoryGroups()
    {
        return ['default'];
    }
}
