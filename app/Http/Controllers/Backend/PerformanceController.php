<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:performance.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:performance.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:performance.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:performance.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of performance settings
     */
    public function index(Request $request)
    {
        try {
            $query = Performance::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('key', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $performanceSettings = $query->ordered()->paginate(20);

        $categories = Performance::getCategories();
        $types = Performance::getTypes();

        return view('backend.settings-performance.index', compact(
            'performanceSettings',
            'categories',
            'types'
        ));

        } catch (\Exception $e) {
            \Log::error('Performance Controller Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการโหลดข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new performance setting
     */
    public function create()
    {
        $categories = Performance::getCategories();
        $types = Performance::getTypes();
        
        return view('backend.settings-performance.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created performance setting
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:core_performance_settings,key',
            'value' => 'required',
            'type' => 'required|in:' . implode(',', array_keys(Performance::getTypes())),
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:' . implode(',', array_keys(Performance::getCategories())),
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'validation_rules' => 'nullable|array',
            'default_value' => 'nullable',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $performance = new Performance();
            $performance->fill($request->all());
            $performance->created_by = Auth::id();
            $performance->updated_by = Auth::id();
            
            // Set typed value
            $performance->setTypedValue($request->value);
            
            // Validate value
            if (!$performance->validateValue($request->value)) {
                return redirect()->back()
                    ->withErrors(['value' => 'The value does not meet validation requirements.'])
                    ->withInput();
            }

            $performance->save();

            // Clear cache
            Cache::forget('performance_settings');

            DB::commit();

            // Log activity
            Log::info('Performance setting created', [
                'id' => $performance->id,
                'key' => $performance->key,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('backend.settings-performance.index')
                ->with('success', 'Performance setting created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating performance setting', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while creating the performance setting.')
                ->withInput();
        }
    }

    /**
     * Display the specified performance setting
     */
    public function show(Performance $performance)
    {
        return view('backend.settings-performance.show', compact('performance'));
    }

    /**
     * Show the form for editing the specified performance setting
     */
    public function edit(Performance $performance)
    {
        $categories = Performance::getCategories();
        $types = Performance::getTypes();
        
        return view('backend.settings-performance.edit', compact('performance', 'categories', 'types'));
    }

    /**
     * Update the specified performance setting
     */
    public function update(Request $request, Performance $performance)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:core_performance_settings,key,' . $performance->id,
            'value' => 'required',
            'type' => 'required|in:' . implode(',', array_keys(Performance::getTypes())),
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:' . implode(',', array_keys(Performance::getCategories())),
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'validation_rules' => 'nullable|array',
            'default_value' => 'nullable',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $oldValue = $performance->value;
            
            $performance->fill($request->all());
            $performance->updated_by = Auth::id();
            
            // Set typed value
            $performance->setTypedValue($request->value);
            
            // Validate value
            if (!$performance->validateValue($request->value)) {
                return redirect()->back()
                    ->withErrors(['value' => 'The value does not meet validation requirements.'])
                    ->withInput();
            }

            $performance->save();

            // Clear cache
            Cache::forget('performance_settings');

            DB::commit();

            // Log activity
            Log::info('Performance setting updated', [
                'id' => $performance->id,
                'key' => $performance->key,
                'old_value' => $oldValue,
                'new_value' => $performance->value,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('backend.settings-performance.index')
                ->with('success', 'Performance setting updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating performance setting', [
                'error' => $e->getMessage(),
                'performance_id' => $performance->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while updating the performance setting.')
                ->withInput();
        }
    }

    /**
     * Remove the specified performance setting
     */
    public function destroy(Performance $performance)
    {
        try {
            DB::beginTransaction();

            $performanceKey = $performance->key;
            $performance->delete();

            // Clear cache
            Cache::forget('performance_settings');

            DB::commit();

            // Log activity
            Log::info('Performance setting deleted', [
                'id' => $performance->id,
                'key' => $performanceKey,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('backend.settings-performance.index')
                ->with('success', 'Performance setting deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting performance setting', [
                'error' => $e->getMessage(),
                'performance_id' => $performance->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while deleting the performance setting.');
        }
    }

    /**
     * Reset performance setting to default value
     */
    public function reset(Performance $performance)
    {
        try {
            DB::beginTransaction();

            $oldValue = $performance->value;
            $performance->value = $performance->default_value;
            $performance->updated_by = Auth::id();
            $performance->save();

            // Clear cache
            Cache::forget('performance_settings');

            DB::commit();

            // Log activity
            Log::info('Performance setting reset to default', [
                'id' => $performance->id,
                'key' => $performance->key,
                'old_value' => $oldValue,
                'default_value' => $performance->default_value,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('success', 'Performance setting reset to default value successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error resetting performance setting', [
                'error' => $e->getMessage(),
                'performance_id' => $performance->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while resetting the performance setting.');
        }
    }

    /**
     * Bulk update performance settings
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:core_performance_settings,id',
            'settings.*.value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $updatedCount = 0;
            foreach ($request->settings as $settingData) {
                $performance = Performance::find($settingData['id']);
                if ($performance) {
                    $oldValue = $performance->value;
                    $performance->setTypedValue($settingData['value']);
                    
                    if ($performance->validateValue($settingData['value'])) {
                        $performance->updated_by = Auth::id();
                        $performance->save();
                        $updatedCount++;
                    }
                }
            }

            // Clear cache
            Cache::forget('performance_settings');

            DB::commit();

            // Log activity
            Log::info('Bulk performance settings update', [
                'updated_count' => $updatedCount,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updatedCount} performance settings.",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk performance settings update', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating performance settings.'
            ], 500);
        }
    }

    /**
     * Export performance settings
     */
    public function export(Request $request)
    {
        $query = Performance::query();

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $performanceSettings = $query->ordered()->get();

        $csvData = [];
        $csvData[] = ['Name', 'Key', 'Value', 'Type', 'Category', 'Description', 'Active', 'Sort Order'];

        foreach ($performanceSettings as $setting) {
            $csvData[] = [
                $setting->name,
                $setting->key,
                $setting->value,
                $setting->type,
                $setting->category,
                $setting->description,
                $setting->is_active ? 'Yes' : 'No',
                $setting->sort_order,
            ];
        }

        $filename = 'performance_settings_' . date('Y-m-d_H-i-s') . '.csv';
        
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
}
