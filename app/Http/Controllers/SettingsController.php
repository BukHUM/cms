<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Save audit settings
     */
    public function saveAuditSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'auditEnabled' => 'required|boolean',
                'auditRetention' => 'required|integer|min:7|max:365',
                'auditLevel' => 'required|string|in:basic,detailed,comprehensive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'audit_enabled' => $request->auditEnabled,
                'audit_retention' => $request->auditRetention,
                'audit_level' => $request->auditLevel
            ];

            // Save each setting to database
            foreach ($settings as $key => $value) {
                DB::table('laravel_settings')->updateOrInsert(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'type' => is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string'),
                        'updated_at' => now()
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่า Audit Log สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit settings
     */
    public function getAuditSettings()
    {
        try {
            $settings = DB::table('laravel_settings')
                ->whereIn('key', ['audit_enabled', 'audit_retention', 'audit_level'])
                ->pluck('value', 'key')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'auditEnabled' => filter_var($settings['audit_enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'auditRetention' => (int)($settings['audit_retention'] ?? 90),
                    'auditLevel' => $settings['audit_level'] ?? 'basic'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }
}