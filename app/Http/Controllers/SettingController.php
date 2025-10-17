<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $query = Setting::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('key', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('group') && $request->group != '') {
            $query->where('group', $request->group);
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_public', $request->status == 'active');
        }

        $settings = $query->orderBy('group')->orderBy('key')->paginate(15);

        // Get available groups and types for filters
        $groups = Setting::distinct()->pluck('group')->filter()->sort()->values();
        $types = Setting::distinct()->pluck('type')->filter()->sort()->values();

        if ($request->expectsJson()) {
            return response()->json($settings);
        }

        return view('backend.settings-general.index', compact('settings', 'groups', 'types'));
    }

    public function create()
    {
        $groups = ['general', 'system', 'email', 'security', 'appearance'];
        $types = ['string', 'boolean', 'integer', 'float', 'email', 'url', 'json'];
        
        return view('backend.settings-general.create', compact('groups', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:core_settings'],
            'value' => ['required'],
            'type' => ['required', 'string', 'in:string,boolean,integer,float,email,url,json'],
            'group' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
        ]);

        // Validate value based on type
        $this->validateValueByType($validated['value'], $validated['type']);

        $setting = Setting::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Setting created successfully', 'setting' => $setting], 201);
        }

        return redirect()->route('backend.settings.index')->with('success', 'สร้างการตั้งค่าใหม่เรียบร้อยแล้ว');
    }

    public function show(Request $request, Setting $setting)
    {
        if ($request->expectsJson()) {
            return response()->json($setting);
        }

        return view('backend.settings-general.show', compact('setting'));
    }

    public function edit(Setting $setting)
    {
        $groups = ['general', 'system', 'email', 'security', 'appearance'];
        $types = ['string', 'boolean', 'integer', 'float', 'email', 'url', 'json'];
        
        return view('backend.settings-general.edit', compact('setting', 'groups', 'types'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', Rule::unique('core_settings')->ignore($setting->id)],
            'value' => ['required'],
            'type' => ['required', 'string', 'in:string,boolean,integer,float,email,url,json'],
            'group' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
        ]);

        // Validate value based on type
        $this->validateValueByType($validated['value'], $validated['type']);

        $setting->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Setting updated successfully', 'setting' => $setting]);
        }

        return redirect()->route('backend.settings.index')->with('success', 'อัปเดตการตั้งค่าเรียบร้อยแล้ว');
    }

    public function destroy(Request $request, Setting $setting)
    {
        if ($setting->isSystemSetting()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Cannot delete system setting'], 403);
            }
            return redirect()->back()->with('error', 'ไม่สามารถลบการตั้งค่าระบบได้');
        }

        $setting->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Setting deleted successfully'], 204);
        }

        return redirect()->route('backend.settings.index')->with('success', 'ลบการตั้งค่าเรียบร้อยแล้ว');
    }

    public function toggleStatus(Request $request, Setting $setting)
    {
        if ($setting->isSystemSetting()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Cannot change status of system setting'], 403);
            }
            return redirect()->back()->with('error', 'ไม่สามารถเปลี่ยนสถานะของการตั้งค่าระบบได้');
        }

        $setting->update(['is_public' => !$setting->is_public]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Setting status updated successfully',
                'setting' => $setting
            ]);
        }

        return redirect()->back()->with('success', 'สถานะการตั้งค่าถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:delete,activate,deactivate'],
            'settings' => ['required', 'array', 'min:1'],
            'settings.*' => ['exists:core_settings,id'],
        ]);

        $settings = Setting::whereIn('id', $validated['settings']);

        // Check if any system settings are included
        $systemSettings = $settings->where('group', 'system')->count();
        if ($systemSettings > 0 && $validated['action'] === 'delete') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Cannot delete system settings'], 403);
            }
            return redirect()->back()->with('error', 'ไม่สามารถลบการตั้งค่าระบบได้');
        }

        switch ($validated['action']) {
            case 'delete':
                $settings->delete();
                $message = 'ลบการตั้งค่าที่เลือกเรียบร้อยแล้ว';
                break;
            case 'activate':
                $settings->update(['is_public' => true]);
                $message = 'เปิดใช้งานการตั้งค่าที่เลือกเรียบร้อยแล้ว';
                break;
            case 'deactivate':
                $settings->update(['is_public' => false]);
                $message = 'ปิดใช้งานการตั้งค่าที่เลือกเรียบร้อยแล้ว';
                break;
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    private function validateValueByType($value, $type)
    {
        switch ($type) {
            case 'boolean':
                if (!in_array($value, ['0', '1', 'true', 'false', 'on', 'off'])) {
                    throw new \InvalidArgumentException('Invalid boolean value');
                }
                break;
            case 'integer':
                if (!is_numeric($value) || (int)$value != $value) {
                    throw new \InvalidArgumentException('Invalid integer value');
                }
                break;
            case 'float':
                if (!is_numeric($value)) {
                    throw new \InvalidArgumentException('Invalid float value');
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new \InvalidArgumentException('Invalid email format');
                }
                break;
            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException('Invalid URL format');
                }
                break;
            case 'json':
                json_decode($value);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \InvalidArgumentException('Invalid JSON format');
                }
                break;
        }
    }
}