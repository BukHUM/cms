<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Carbon\Carbon;

class SettingsBackupController extends BaseSettingsController
{
    protected $category = 'backup';
    protected $viewPath = 'backend.settings-backup';
    protected $routePrefix = 'backend.settings-backup';

    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:backup.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:backup.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:backup.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:backup.delete', ['only' => ['destroy']]);
    }

    /**
     * Get available groups for backup settings
     */
    protected function getCategoryGroups()
    {
        return [
            'database' => 'การตั้งค่าสำรองฐานข้อมูล',
            'files' => 'การตั้งค่าสำรองไฟล์',
            'schedule' => 'การตั้งค่าการสำรองอัตโนมัติ',
            'storage' => 'การตั้งค่าที่เก็บข้อมูล',
            'retention' => 'การตั้งค่าการเก็บข้อมูล',
            'notification' => 'การตั้งค่าการแจ้งเตือน',
        ];
    }

    /**
     * Create database backup
     */
    public function createBackup(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'backup_name' => 'nullable|string|max:255',
            'include_files' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $backupName = $request->backup_name ?: 'backup_' . Carbon::now()->format('Y-m-d_H-i-s');
            $backupPath = 'backups/' . $backupName;
            
            // Create backup directory
            Storage::makeDirectory($backupPath);

            // Export database
            $this->exportDatabase($backupPath);

            // Include files if requested
            if ($request->include_files) {
                $this->backupFiles($backupPath);
            }

            // Create backup info file
            $this->createBackupInfo($backupPath, $backupName, $request->include_files);

            return response()->json([
                'success' => true,
                'message' => 'สำรองข้อมูลเรียบร้อยแล้ว',
                'backup_name' => $backupName
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการสำรองข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get backup list
     */
    public function getBackups()
    {
        $backups = [];
        $backupPath = storage_path('app/backups');

        if (is_dir($backupPath)) {
            $directories = array_diff(scandir($backupPath), ['.', '..']);
            
            foreach ($directories as $dir) {
                $dirPath = $backupPath . '/' . $dir;
                if (is_dir($dirPath)) {
                    $infoFile = $dirPath . '/backup_info.json';
                    $info = [];
                    
                    if (file_exists($infoFile)) {
                        $info = json_decode(file_get_contents($infoFile), true);
                    }
                    
                    $backups[] = [
                        'name' => $dir,
                        'path' => $dir,
                        'created_at' => $info['created_at'] ?? filemtime($dirPath),
                        'size' => $this->getDirectorySize($dirPath),
                        'include_files' => $info['include_files'] ?? false,
                    ];
                }
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return response()->json([
            'success' => true,
            'backups' => $backups
        ]);
    }

    /**
     * Download backup
     */
    public function downloadBackup($backupName)
    {
        $backupPath = storage_path('app/backups/' . $backupName);
        
        if (!is_dir($backupPath)) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบไฟล์สำรองข้อมูล'
            ], 404);
        }

        // Create zip file
        $zipFile = storage_path('app/temp/' . $backupName . '.zip');
        $this->createZip($backupPath, $zipFile);

        return response()->download($zipFile, $backupName . '.zip')->deleteFileAfterSend(true);
    }

    /**
     * Delete backup
     */
    public function deleteBackup($backupName)
    {
        $backupPath = storage_path('app/backups/' . $backupName);
        
        if (!is_dir($backupPath)) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบไฟล์สำรองข้อมูล'
            ], 404);
        }

        try {
            $this->deleteDirectory($backupPath);
            
            return response()->json([
                'success' => true,
                'message' => 'ลบไฟล์สำรองข้อมูลเรียบร้อยแล้ว'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการลบไฟล์สำรองข้อมูล'
            ], 500);
        }
    }

    /**
     * Export database to SQL file
     */
    private function exportDatabase($backupPath)
    {
        $database = config('database.connections.sqlite.database');
        $backupFile = storage_path('app/' . $backupPath . '/database.sql');
        
        // For SQLite, we can simply copy the database file
        if (file_exists($database)) {
            copy($database, storage_path('app/' . $backupPath . '/database.sqlite'));
        }
    }

    /**
     * Backup important files
     */
    private function backupFiles($backupPath)
    {
        $filesToBackup = [
            'storage/app/public',
            'storage/logs',
            'config',
            'database/migrations',
        ];

        foreach ($filesToBackup as $file) {
            $sourcePath = base_path($file);
            $destPath = storage_path('app/' . $backupPath . '/' . basename($file));
            
            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            }
        }
    }

    /**
     * Copy directory recursively
     */
    private function copyDirectory($source, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName(), 0755, true);
            } else {
                copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }

    /**
     * Create backup info file
     */
    private function createBackupInfo($backupPath, $backupName, $includeFiles)
    {
        $info = [
            'name' => $backupName,
            'created_at' => Carbon::now()->toISOString(),
            'include_files' => $includeFiles,
            'database_type' => config('database.default'),
            'app_version' => config('app.version', '1.0.0'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        file_put_contents(
            storage_path('app/' . $backupPath . '/backup_info.json'),
            json_encode($info, JSON_PRETTY_PRINT)
        );
    }

    /**
     * Get directory size
     */
    private function getDirectorySize($directory)
    {
        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            $size += $file->getSize();
        }

        return $this->formatBytes($size);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Create zip file
     */
    private function createZip($source, $destination)
    {
        $zip = new \ZipArchive();
        $zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else {
                $zip->addFile($file, str_replace($source . '/', '', $file));
            }
        }

        $zip->close();
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
}