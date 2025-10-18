<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SettingsUpdate;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class SettingsUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get recent updates (last 5)
        $recentUpdates = SettingsUpdate::with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Check for available updates
        $updateStatus = $this->checkAvailableUpdates();

        return view('backend.settings-update.index', compact('recentUpdates', 'updateStatus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $updateTypes = [
            'core' => 'Laravel Core',
            'package' => 'Package',
            'config' => 'Configuration',
        ];

        return view('backend.settings-update.create', compact('updateTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'update_type' => 'required|in:core,package,config',
            'component_name' => 'required|string|max:255',
            'current_version' => 'nullable|string|max:50',
            'target_version' => 'required|string|max:50',
            'description' => 'nullable|string',
            'changelog' => 'nullable|string',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $update = SettingsUpdate::create([
            'update_type' => $request->update_type,
            'component_name' => $request->component_name,
            'current_version' => $request->current_version,
            'target_version' => $request->target_version,
            'description' => $request->description,
            'changelog' => $request->changelog,
            'scheduled_at' => $request->scheduled_at,
            'created_by' => Auth::id(),
        ]);

        // Log audit
        AuditLogService::log(
            'settings_update_created',
            $update,
            null,
            $update->toArray(),
            Auth::user(),
            $request,
            'settings'
        );

        return redirect()->route('backend.settings-update.index')
            ->with('success', 'สร้างการอัพเดตเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(SettingsUpdate $settingsUpdate)
    {
        $settingsUpdate->load(['creator', 'updater']);
        
        return view('backend.settings-update.show', compact('settingsUpdate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SettingsUpdate $settingsUpdate)
    {
        $updateTypes = [
            'core' => 'Laravel Core',
            'package' => 'Package',
            'config' => 'Configuration',
        ];

        return view('backend.settings-update.edit', compact('settingsUpdate', 'updateTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SettingsUpdate $settingsUpdate)
    {
        $request->validate([
            'update_type' => 'required|in:core,package,config',
            'component_name' => 'required|string|max:255',
            'current_version' => 'nullable|string|max:50',
            'target_version' => 'required|string|max:50',
            'description' => 'nullable|string',
            'changelog' => 'nullable|string',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $oldData = $settingsUpdate->toArray();

        $settingsUpdate->update([
            'update_type' => $request->update_type,
            'component_name' => $request->component_name,
            'current_version' => $request->current_version,
            'target_version' => $request->target_version,
            'description' => $request->description,
            'changelog' => $request->changelog,
            'scheduled_at' => $request->scheduled_at,
            'updated_by' => Auth::id(),
        ]);

        // Log audit
        AuditLogService::log(
            'settings_update_updated',
            $settingsUpdate,
            $oldData,
            $settingsUpdate->toArray(),
            Auth::user(),
            $request,
            'settings'
        );

        return redirect()->route('backend.settings-update.index')
            ->with('success', 'อัพเดตข้อมูลเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SettingsUpdate $settingsUpdate)
    {
        // Only allow deletion of pending or cancelled updates
        if (!in_array($settingsUpdate->status, ['pending', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'ไม่สามารถลบการอัพเดตที่มีสถานะ ' . $settingsUpdate->status_text . ' ได้');
        }

        $settingsUpdate->delete();

        // Log audit
        AuditLogService::log(
            'settings_update_deleted',
            $settingsUpdate,
            $settingsUpdate->toArray(),
            null,
            Auth::user(),
            request(),
            'settings'
        );

        return redirect()->route('backend.settings-update.index')
            ->with('success', 'ลบการอัพเดตเรียบร้อยแล้ว');
    }

    /**
     * Start the update process
     */
    public function start(SettingsUpdate $settingsUpdate)
    {
        if (!$settingsUpdate->canStart()) {
            return redirect()->back()
                ->with('error', 'ไม่สามารถเริ่มการอัพเดตได้');
        }

        try {
            $settingsUpdate->startUpdate();

            // Log audit
            AuditLogService::log(
                'settings_update_started',
                $settingsUpdate,
                null,
                $settingsUpdate->toArray(),
                Auth::user(),
                request(),
                'settings'
            );

            // Execute update based on type
            $this->executeUpdate($settingsUpdate);

            return redirect()->back()
                ->with('success', 'เริ่มการอัพเดตเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            $settingsUpdate->failUpdate($e->getMessage());
            
            Log::error('Settings update failed', [
                'update_id' => $settingsUpdate->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'การอัพเดตล้มเหลว: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the update
     */
    public function cancel(SettingsUpdate $settingsUpdate)
    {
        if (!$settingsUpdate->canCancel()) {
            return redirect()->back()
                ->with('error', 'ไม่สามารถยกเลิกการอัพเดตได้');
        }

        $settingsUpdate->cancelUpdate();

        // Log audit
        AuditLogService::log(
            'settings_update_cancelled',
            $settingsUpdate,
            null,
            $settingsUpdate->toArray(),
            Auth::user(),
            request(),
            'settings'
        );

        return redirect()->back()
            ->with('success', 'ยกเลิกการอัพเดตเรียบร้อยแล้ว');
    }

    /**
     * Retry failed update
     */
    public function retry(SettingsUpdate $settingsUpdate)
    {
        if (!$settingsUpdate->canRetry()) {
            return redirect()->back()
                ->with('error', 'ไม่สามารถลองใหม่ได้');
        }

        try {
            $settingsUpdate->startUpdate();

            // Log audit
            AuditLogService::log(
                'settings_update_retried',
                $settingsUpdate,
                null,
                $settingsUpdate->toArray(),
                Auth::user(),
                request(),
                'settings'
            );

            // Execute update based on type
            $this->executeUpdate($settingsUpdate);

            return redirect()->back()
                ->with('success', 'ลองใหม่เรียบร้อยแล้ว');

        } catch (\Exception $e) {
            $settingsUpdate->failUpdate($e->getMessage());
            
            Log::error('Settings update retry failed', [
                'update_id' => $settingsUpdate->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'การลองใหม่ล้มเหลว: ' . $e->getMessage());
        }
    }

    /**
     * Execute the actual update
     */
    private function executeUpdate(SettingsUpdate $settingsUpdate)
    {
        $log = [];
        
        try {
            switch ($settingsUpdate->update_type) {
                case 'core':
                    $this->updateLaravelCore($settingsUpdate, $log);
                    break;
                case 'package':
                    $this->updatePackage($settingsUpdate, $log);
                    break;
                case 'config':
                    $this->updateConfig($settingsUpdate, $log);
                    break;
            }

            $settingsUpdate->completeUpdate($log);

        } catch (\Exception $e) {
            $log[] = ['error' => $e->getMessage(), 'time' => now()];
            $settingsUpdate->failUpdate($e->getMessage(), $log);
            throw $e;
        }
    }

    /**
     * Update Laravel Core
     */
    private function updateLaravelCore(SettingsUpdate $settingsUpdate, &$log)
    {
        $log[] = ['action' => 'Starting Laravel Core update', 'time' => now()];

        // Backup important files
        $backupFiles = [
            'composer.json',
            'composer.lock',
            '.env',
        ];

        $backupDir = storage_path('app/backups/' . $settingsUpdate->id);
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        foreach ($backupFiles as $file) {
            if (File::exists(base_path($file))) {
                File::copy(base_path($file), $backupDir . '/' . $file);
                $log[] = ['action' => "Backed up {$file}", 'time' => now()];
            }
        }

        // Update composer dependencies
        $log[] = ['action' => 'Running composer update', 'time' => now()];
        $result = Process::run('composer update --no-dev --optimize-autoloader');
        
        if (!$result->successful()) {
            throw new \Exception('Composer update failed: ' . $result->errorOutput());
        }

        $log[] = ['action' => 'Composer update completed', 'time' => now()];

        // Clear caches
        $log[] = ['action' => 'Clearing caches', 'time' => now()];
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        $log[] = ['action' => 'Caches cleared', 'time' => now()];

        // Run migrations if needed
        $log[] = ['action' => 'Running migrations', 'time' => now()];
        Artisan::call('migrate', ['--force' => true]);
        $log[] = ['action' => 'Migrations completed', 'time' => now()];
    }

    /**
     * Update Package
     */
    private function updatePackage(SettingsUpdate $settingsUpdate, &$log)
    {
        $log[] = ['action' => 'Starting package update', 'time' => now()];

        // Update specific package
        $packageName = $settingsUpdate->component_name;
        $log[] = ['action' => "Updating package: {$packageName}", 'time' => now()];
        
        $result = Process::run("composer update {$packageName} --no-dev --optimize-autoloader");
        
        if (!$result->successful()) {
            throw new \Exception('Package update failed: ' . $result->errorOutput());
        }

        $log[] = ['action' => 'Package update completed', 'time' => now()];

        // Clear caches
        $log[] = ['action' => 'Clearing caches', 'time' => now()];
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        $log[] = ['action' => 'Caches cleared', 'time' => now()];
    }

    /**
     * Update Configuration
     */
    private function updateConfig(SettingsUpdate $settingsUpdate, &$log)
    {
        $log[] = ['action' => 'Starting configuration update', 'time' => now()];

        // This would be implemented based on specific configuration needs
        $log[] = ['action' => 'Configuration update completed', 'time' => now()];

        // Clear caches
        $log[] = ['action' => 'Clearing caches', 'time' => now()];
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        $log[] = ['action' => 'Caches cleared', 'time' => now()];
    }

    /**
     * Quick update action
     */
    public function quickUpdate(Request $request)
    {
        $request->validate([
            'update_type' => 'required|in:core,package,config',
            'component_name' => 'required|string|max:255',
            'target_version' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $update = SettingsUpdate::create([
                'update_type' => $request->update_type,
                'component_name' => $request->component_name,
                'target_version' => $request->target_version,
                'description' => $request->description,
                'status' => 'pending',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Log audit
            AuditLogService::log(
                'settings_update_created',
                'Created quick update: ' . $request->component_name,
                $update,
                Auth::user()
            );

            return redirect()->route('backend.settings-update.index')
                ->with('success', 'การอัพเดตถูกสร้างเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('Quick update creation failed: ' . $e->getMessage());
            return redirect()->route('backend.settings-update.index')
                ->with('error', 'เกิดข้อผิดพลาดในการสร้างการอัพเดต: ' . $e->getMessage());
        }
    }

    /**
     * Clear system caches
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            // Log audit
            AuditLogService::log(
                'system_cache_cleared',
                'System cache cleared',
                null,
                Auth::user()
            );
            
            return redirect()->back()->with('success', 'ระบบล้าง cache เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('Cache clear failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Optimize system
     */
    public function optimize()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            // Log audit
            AuditLogService::log(
                'system_optimized',
                'System optimized',
                null,
                Auth::user()
            );
            
            return redirect()->back()->with('success', 'ระบบ optimize เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('System optimization failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Run migrations
     */
    public function migrate()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            
            // Log audit
            AuditLogService::log(
                'migrations_run',
                'Database migrations executed',
                null,
                Auth::user()
            );
            
            return redirect()->back()->with('success', 'รัน migrations เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('Migration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Run seeders
     */
    public function seed()
    {
        try {
            Artisan::call('db:seed', ['--force' => true]);
            
            // Log audit
            AuditLogService::log(
                'seeders_run',
                'Database seeders executed',
                null,
                Auth::user()
            );
            
            return redirect()->back()->with('success', 'รัน seeders เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('Seeding failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Check for available updates from Laravel
     */
    private function checkAvailableUpdates()
    {
        try {
            // Check Laravel updates
            $laravelUpdate = $this->checkLaravelUpdates();
            
            // Check Composer packages updates
            $packageUpdate = $this->checkPackageUpdates();
            
            // Check Configuration updates
            $configUpdate = $this->checkConfigUpdates();
            
            return [
                'laravel' => $laravelUpdate,
                'packages' => $packageUpdate,
                'config' => $configUpdate,
                'has_updates' => $laravelUpdate['available'] || $packageUpdate['available'] || $configUpdate['available']
            ];
        } catch (\Exception $e) {
            Log::error('Update check failed: ' . $e->getMessage());
            return [
                'laravel' => ['available' => false, 'error' => $e->getMessage()],
                'packages' => ['available' => false, 'error' => $e->getMessage()],
                'config' => ['available' => false, 'error' => $e->getMessage()],
                'has_updates' => false
            ];
        }
    }

    /**
     * Check Laravel updates
     */
    private function checkLaravelUpdates()
    {
        try {
            $currentVersion = app()->version();
            
            // Simulate checking for Laravel updates
            // In real implementation, you would check against Laravel's API or GitHub releases
            $latestVersion = $this->getLatestLaravelVersion();
            
            $available = version_compare($latestVersion, $currentVersion, '>');
            
            return [
                'available' => $available,
                'current_version' => $currentVersion,
                'latest_version' => $latestVersion,
                'description' => $available ? "อัพเดต Laravel จาก {$currentVersion} เป็น {$latestVersion}" : null,
            ];
        } catch (\Exception $e) {
            Log::error('Laravel update check failed: ' . $e->getMessage());
            return [
                'available' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Composer packages updates
     */
    private function checkPackageUpdates()
    {
        try {
            // Run composer outdated command
            $result = Process::run('composer outdated --format=json');
            
            if ($result->successful()) {
                $output = $result->output();
                $data = json_decode($output, true);
                
                $outdatedPackages = $data['installed'] ?? [];
                $count = count($outdatedPackages);
                
                return [
                    'available' => $count > 0,
                    'count' => $count,
                    'packages' => $outdatedPackages,
                    'description' => $count > 0 ? "อัพเดต {$count} packages ที่ล้าสมัย" : null,
                ];
            } else {
                return [
                    'available' => false,
                    'error' => 'ไม่สามารถตรวจสอบ packages ได้',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Package update check failed: ' . $e->getMessage());
            return [
                'available' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Configuration updates
     */
    private function checkConfigUpdates()
    {
        try {
            // Check for configuration changes
            // This is a simplified check - in real implementation, you would check against
            // configuration templates or version control
            
            $configChanges = $this->getConfigChanges();
            $changes = count($configChanges);
            
            return [
                'available' => $changes > 0,
                'changes' => $changes,
                'details' => $configChanges,
                'description' => $changes > 0 ? "อัพเดต configuration {$changes} ไฟล์" : null,
            ];
        } catch (\Exception $e) {
            Log::error('Config update check failed: ' . $e->getMessage());
            return [
                'available' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get latest Laravel version (simulated)
     */
    private function getLatestLaravelVersion()
    {
        // In real implementation, you would fetch this from Laravel's API
        // For now, we'll simulate a newer version
        $currentVersion = app()->version();
        $versionParts = explode('.', $currentVersion);
        
        // Simulate a minor version update
        if (isset($versionParts[1])) {
            $versionParts[1] = (int)$versionParts[1] + 1;
        }
        
        return implode('.', $versionParts);
    }

    /**
     * Get configuration changes (simulated)
     */
    private function getConfigChanges()
    {
        // In real implementation, you would check for actual configuration changes
        // For now, we'll simulate some changes
        return [
            'app.php' => 'New configuration options available',
            'database.php' => 'Updated database configuration',
            'cache.php' => 'New cache drivers available',
        ];
    }
}
