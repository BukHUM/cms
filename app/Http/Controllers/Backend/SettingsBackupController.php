<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Validator;
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
     * Display a listing of settings
     */
    public function index(Request $request)
    {
        try {
            // Get backup settings grouped by category
            $settings = $this->model->where('category', $this->category)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('key')
                ->get()
                ->groupBy('group_name');

            // Get backup statistics
            $backupStats = $this->getBackupStatistics();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'settings' => $settings,
                    'statistics' => $backupStats
                ]);
            }

            return view("{$this->viewPath}.index", compact('settings', 'backupStats'));

        } catch (\Exception $e) {
            \Log::error("Settings Controller Index Error ({$this->category}): " . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการโหลดข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * Get backup statistics
     */
    private function getBackupStatistics()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                return [
                    'total_backups' => 0,
                    'total_size' => 0,
                    'last_backup' => null,
                    'storage_path' => $backupPath
                ];
            }

            $files = glob($backupPath . '/*');
            $totalSize = 0;
            $lastBackup = null;

            foreach ($files as $file) {
                if (is_file($file)) {
                    $totalSize += filesize($file);
                    $fileTime = filemtime($file);
                    if (!$lastBackup || $fileTime > $lastBackup) {
                        $lastBackup = $fileTime;
                    }
                }
            }

            return [
                'total_backups' => count($files),
                'total_size' => $totalSize,
                'last_backup' => $lastBackup ? Carbon::createFromTimestamp($lastBackup) : null,
                'storage_path' => $backupPath
            ];

        } catch (\Exception $e) {
            \Log::error("Error getting backup statistics: " . $e->getMessage());
            return [
                'total_backups' => 0,
                'total_size' => 0,
                'last_backup' => null,
                'storage_path' => storage_path('app/backups')
            ];
        }
    }

    /**
     * Update backup settings
     */
    public function updateSettings(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'settings' => 'required|array',
                'settings.*.key' => 'required|string',
                'settings.*.value' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updatedCount = 0;
            foreach ($request->settings as $settingData) {
                $setting = Setting::where('key', $settingData['key'])
                    ->where('category', $this->category)
                    ->first();

                if ($setting) {
                    $setting->value = $settingData['value'];
                    $setting->updated_by = auth()->id();
                    $setting->save();
                    $updatedCount++;
                }
            }

            // Clear cache
            \App\Services\SettingsService::clearCache();

            return response()->json([
                'success' => true,
                'message' => "บันทึกการตั้งค่าเรียบร้อยแล้ว",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            \Log::error("Error updating backup settings: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตการตั้งค่า: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create database backup
     */
    public function createBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_name' => 'nullable|string|max:255',
            'include_files' => 'required|in:true,false,1,0',
            'include_database' => 'required|in:true,false,1,0',
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
            $backupZipPath = 'backups/' . $backupName . '.zip';
            
            \Log::info("Starting backup process", [
                'backup_name' => $backupName,
                'include_database' => $request->include_database,
                'include_files' => $request->include_files
            ]);
            
            // Create temporary directory for backup files
            $tempPath = 'backups/temp_' . $backupName;
            $tempDirPath = storage_path('app/' . $tempPath);
            if (!is_dir($tempDirPath)) {
                mkdir($tempDirPath, 0755, true);
            }
            
            \Log::info("Created temporary directory", ['temp_path' => $tempPath]);

            // Export database if requested
            if ($request->include_database === 'true' || $request->include_database === '1') {
                \Log::info("Exporting database");
                $this->exportDatabase($tempPath);
                \Log::info("Database export completed");
            }

            // Include files if requested
            if ($request->include_files === 'true' || $request->include_files === '1') {
                \Log::info("Backing up files");
                $this->backupFiles($tempPath);
                \Log::info("Files backup completed");
            }

            // Create backup info file
            \Log::info("Creating backup info file");
            $this->createBackupInfo($tempPath, $backupName, 
                $request->include_files === 'true' || $request->include_files === '1',
                $request->include_database === 'true' || $request->include_database === '1'
            );

            // Create ZIP archive
            \Log::info("Creating ZIP archive", ['zip_path' => $backupZipPath]);
            $this->createZipArchive($tempPath, $backupZipPath);
            
            // Clean up temporary directory
            \Log::info("Cleaning up temporary directory", ['temp_path' => $tempPath]);
            $tempDirPath = storage_path('app/' . $tempPath);
            if (is_dir($tempDirPath)) {
                $this->deleteDirectory($tempDirPath);
                \Log::info("Temporary directory deleted successfully");
            }

            \Log::info("Backup process completed successfully", ['backup_name' => $backupName . '.zip']);

            return response()->json([
                'success' => true,
                'message' => 'สำรองข้อมูลเรียบร้อยแล้ว',
                'backup_name' => $backupName . '.zip'
            ]);

        } catch (\Exception $e) {
            \Log::error("Backup process failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการสำรองข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified backup setting
     */
    public function show(Setting $settingsBackup)
    {
        if (request()->expectsJson()) {
            return response()->json($settingsBackup);
        }
        
        return view("{$this->viewPath}.show", compact('settingsBackup'));
    }

    /**
     * Get backup list
     */
    public function getBackups()
    {
        try {
            $backups = [];
            $backupPath = storage_path('app/backups');

            // Create backup directory if it doesn't exist
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            if (is_dir($backupPath)) {
                $files = array_diff(scandir($backupPath), ['.', '..']);
                
                foreach ($files as $file) {
                    $filePath = $backupPath . '/' . $file;
                    
                    // Handle ZIP files
                    if (is_file($filePath) && pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                        // Try to read backup info from ZIP file
                        $zip = new \ZipArchive();
                        $includeFiles = false;
                        $includeDatabase = false;
                        
                        if ($zip->open($filePath) === TRUE) {
                            // Check if backup_info.json exists in ZIP
                            $infoIndex = $zip->locateName('backup_info.json');
                            if ($infoIndex !== false) {
                                $infoContent = $zip->getFromIndex($infoIndex);
                                if ($infoContent !== false) {
                                    $info = json_decode($infoContent, true);
                                    if ($info) {
                                        $includeFiles = $info['include_files'] ?? false;
                                        $includeDatabase = $info['include_database'] ?? false;
                                    }
                                }
                            } else {
                                // Fallback: check for database.sql and other files
                                $includeDatabase = $zip->locateName('database.sql') !== false;
                                $includeFiles = $zip->locateName('config') !== false || $zip->locateName('storage') !== false;
                            }
                            $zip->close();
                        }
                        
                        $backups[] = [
                            'name' => $file,
                            'path' => $file,
                            'created_at' => Carbon::createFromTimestamp(filemtime($filePath))->setTimezone(config('app.timezone', 'UTC'))->toISOString(),
                            'size' => filesize($filePath),
                            'include_files' => $includeFiles,
                            'include_database' => $includeDatabase,
                            'type' => 'zip'
                        ];
                    }
                    // Handle directories (legacy support)
                    elseif (is_dir($filePath)) {
                        $infoFile = $filePath . '/backup_info.json';
                        $info = [];
                        
                        if (file_exists($infoFile)) {
                            $infoContent = file_get_contents($infoFile);
                            if ($infoContent !== false) {
                                $info = json_decode($infoContent, true) ?: [];
                            }
                        }
                        
                        $backups[] = [
                            'name' => $file,
                            'path' => $file,
                            'created_at' => $info['created_at'] ?? Carbon::createFromTimestamp(filemtime($filePath))->setTimezone(config('app.timezone', 'UTC'))->toISOString(),
                            'size' => $this->getDirectorySize($filePath),
                            'include_files' => $info['include_files'] ?? false,
                            'include_database' => $info['include_database'] ?? false,
                            'type' => 'directory'
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

        } catch (\Exception $e) {
            \Log::error('Error in getBackups: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการโหลดรายการสำรองข้อมูล: ' . $e->getMessage(),
                'backups' => []
            ], 500);
        }
    }

    /**
     * Download backup
     */
    public function downloadBackup($backupName)
    {
        $backupPath = storage_path('app/backups/' . $backupName);
        
        // Check if it's a ZIP file
        if (is_file($backupPath) && pathinfo($backupName, PATHINFO_EXTENSION) === 'zip') {
            if (!file_exists($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบไฟล์สำรองข้อมูล'
                ], 404);
            }
            
            return response()->download($backupPath, $backupName);
        }
        
        // Handle directory (legacy)
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
        
        try {
            // Check if it's a ZIP file
            if (is_file($backupPath) && pathinfo($backupName, PATHINFO_EXTENSION) === 'zip') {
                if (!file_exists($backupPath)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ไม่พบไฟล์สำรองข้อมูล'
                    ], 404);
                }
                
                unlink($backupPath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'ลบไฟล์สำรองข้อมูลเรียบร้อยแล้ว'
                ]);
            }
            
            // Handle directory (legacy)
            if (!is_dir($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบไฟล์สำรองข้อมูล'
                ], 404);
            }

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
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");
        
        \Log::info("Exporting database", [
            'connection' => $connection,
            'backup_path' => $backupPath
        ]);
        
        $backupFile = storage_path('app/' . $backupPath . '/database.sql');
        
        \Log::info("Backup file path", ['backup_file' => $backupFile]);
        
        try {
            switch ($connection) {
                case 'mysql':
                case 'mariadb':
                    \Log::info("Using MySQL/MariaDB export");
                    $this->exportMySQLDatabase($config, $backupFile);
                    break;
                    
                case 'sqlite':
                    \Log::info("Using SQLite export");
                    $this->exportSQLiteDatabase($config, $backupPath);
                    break;
                    
                case 'pgsql':
                    \Log::info("Using PostgreSQL export");
                    $this->exportPostgreSQLDatabase($config, $backupFile);
                    break;
                    
                default:
                    throw new \Exception("Unsupported database connection: {$connection}");
            }
            
            \Log::info("Database export completed successfully");
            
        } catch (\Exception $e) {
            \Log::error("Database export failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Export MySQL/MariaDB database
     */
    private function exportMySQLDatabase($config, $backupFile)
    {
        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];
        
        // Find mysqldump executable
        $mysqldump = $this->findMySQLDump();
        
        // Build mysqldump command (cross-platform)
        $command = sprintf(
            '"%s" --host=%s --port=%s --user=%s --password=%s --single-transaction --no-tablespaces --skip-routines --skip-triggers %s',
            $mysqldump,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database)
        );
        
        // Execute command and capture output
        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);
        
        // Filter out warnings and check for actual errors (cross-platform)
        $filteredOutput = array_filter($output, function($line) {
            return !preg_match('/^WARNING:/', $line) && 
                   !preg_match('/^mysqldump\.exe :/', $line) &&
                   !preg_match('/^mysqldump: \[Warning\]/', $line);
        });
        
        // Check if we have actual SQL content
        $sqlContent = implode("\n", $output);
        $hasSqlContent = preg_match('/^-- MariaDB dump|^-- MySQL dump|^CREATE TABLE|^INSERT INTO/i', $sqlContent);
        
        if ($returnCode !== 0 && !$hasSqlContent) {
            throw new \Exception("MySQL dump failed with return code: {$returnCode}. Output: " . implode("\n", $output));
        }
        
        // Write output to file
        if (empty($sqlContent)) {
            throw new \Exception("MySQL dump produced empty output");
        }
        
        // Ensure directory exists
        $backupDir = dirname($backupFile);
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        // Write content to file
        $bytesWritten = file_put_contents($backupFile, $sqlContent);
        
        if ($bytesWritten === false || $bytesWritten === 0) {
            throw new \Exception("Failed to write MySQL dump to file");
        }
        
        if (!file_exists($backupFile) || filesize($backupFile) === 0) {
            throw new \Exception("MySQL dump file was not created or is empty");
        }
    }
    
    /**
     * Find mysqldump executable
     */
    private function findMySQLDump()
    {
        // Cross-platform paths
        $possiblePaths = [];
        
        // Windows paths
        if (PHP_OS_FAMILY === 'Windows') {
            $possiblePaths = [
                'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
                'mysqldump', // Try PATH
            ];
        } else {
            // Linux/Unix paths
            $possiblePaths = [
                '/usr/bin/mysqldump',
                '/usr/local/bin/mysqldump',
                '/opt/mysql/bin/mysqldump',
                'mysqldump', // Try PATH
            ];
        }
        
        foreach ($possiblePaths as $path) {
            if ($path === 'mysqldump') {
                // Check if it's in PATH
                $output = [];
                $returnCode = 0;
                if (PHP_OS_FAMILY === 'Windows') {
                    exec('where mysqldump 2>nul', $output, $returnCode);
                } else {
                    exec('which mysqldump 2>/dev/null', $output, $returnCode);
                }
                if ($returnCode === 0 && !empty($output)) {
                    return $output[0];
                }
            } else {
                if (file_exists($path)) {
                    return $path;
                }
            }
        }
        
        throw new \Exception("mysqldump executable not found. Please ensure MySQL is installed and mysqldump is available.");
    }
    
    /**
     * Export SQLite database
     */
    private function exportSQLiteDatabase($config, $backupPath)
    {
        $database = $config['database'];
        
        if (file_exists($database)) {
            Storage::copy($database, $backupPath . '/database.sqlite');
        } else {
            throw new \Exception("SQLite database file not found: {$database}");
        }
    }
    
    /**
     * Export PostgreSQL database
     */
    private function exportPostgreSQLDatabase($config, $backupFile)
    {
        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];
        
        // Set password environment variable
        putenv("PGPASSWORD={$password}");
        
        // Find pg_dump executable
        $pgDump = $this->findPostgreSQLDump();
        
        // Build pg_dump command
        $command = sprintf(
            '"%s" --host=%s --port=%s --username=%s --dbname=%s',
            $pgDump,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($database)
        );
        
        // Execute command and capture output
        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception("PostgreSQL dump failed with return code: {$returnCode}");
        }
        
        // Write output to file
        $sqlContent = implode("\n", $output);
        if (empty($sqlContent)) {
            throw new \Exception("PostgreSQL dump produced empty output");
        }
        
        // Ensure directory exists
        $backupDir = dirname($backupFile);
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        file_put_contents($backupFile, $sqlContent);
        
        if (!file_exists($backupFile) || filesize($backupFile) === 0) {
            throw new \Exception("PostgreSQL dump file was not created or is empty");
        }
    }
    
    /**
     * Find pg_dump executable
     */
    private function findPostgreSQLDump()
    {
        // Cross-platform paths
        $possiblePaths = [];
        
        // Windows paths
        if (PHP_OS_FAMILY === 'Windows') {
            $possiblePaths = [
                'C:\\Program Files\\PostgreSQL\\15\\bin\\pg_dump.exe',
                'C:\\Program Files\\PostgreSQL\\14\\bin\\pg_dump.exe',
                'C:\\Program Files\\PostgreSQL\\13\\bin\\pg_dump.exe',
                'pg_dump', // Try PATH
            ];
        } else {
            // Linux/Unix paths
            $possiblePaths = [
                '/usr/bin/pg_dump',
                '/usr/local/bin/pg_dump',
                '/opt/postgresql/bin/pg_dump',
                'pg_dump', // Try PATH
            ];
        }
        
        foreach ($possiblePaths as $path) {
            if ($path === 'pg_dump') {
                // Check if it's in PATH
                $output = [];
                $returnCode = 0;
                if (PHP_OS_FAMILY === 'Windows') {
                    exec('where pg_dump 2>nul', $output, $returnCode);
                } else {
                    exec('which pg_dump 2>/dev/null', $output, $returnCode);
                }
                if ($returnCode === 0 && !empty($output)) {
                    return $output[0];
                }
            } else {
                if (file_exists($path)) {
                    return $path;
                }
            }
        }
        
        throw new \Exception("pg_dump executable not found. Please ensure PostgreSQL is installed and pg_dump is available.");
    }

    /**
     * Backup important files
     */
    private function backupFiles($backupPath)
    {
        // Core Laravel files (essential for deployment)
        $coreFiles = [
            'artisan',
            'composer.json',
            'composer.lock',
            'package.json',
            'package-lock.json',
            'vite.config.js',
            'tailwind.config.cjs',
            'postcss.config.cjs',
            'phpunit.xml',
            '.env.example',
            'README.md',
            'deploy-production.sh',
            '.editorconfig',
            '.gitattributes',
            '.gitignore',
            '.npmrc'
        ];

        // Copy core files
        foreach ($coreFiles as $file) {
            $sourcePath = base_path($file);
            $destPath = storage_path('app/' . $backupPath . '/' . basename($file));
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destPath);
            }
        }

        // Core directories (essential for Laravel)
        $coreDirectories = [
            'bootstrap' => 'bootstrap',
            'routes' => 'routes',
            'resources' => 'resources',
            'app' => 'app',
            'config' => 'config',
            'database' => 'database',
            'storage/logs' => 'storage/logs',
        ];

        foreach ($coreDirectories as $source => $dest) {
            $sourcePath = base_path($source);
            $destPath = storage_path('app/' . $backupPath . '/' . $dest);
            
            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            }
        }
        
        // Handle public directory separately (skip symbolic links)
        $publicPath = base_path('public');
        $publicDestPath = storage_path('app/' . $backupPath . '/public');
        
        if (is_dir($publicPath)) {
            $this->copyDirectorySafe($publicPath, $publicDestPath);
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
            $targetPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            
            if ($item->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                // Ensure target directory exists
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                // Use file_get_contents and file_put_contents for safer copying
                $content = file_get_contents($item->getPathname());
                if ($content !== false) {
                    file_put_contents($targetPath, $content);
                }
            }
        }
    }

    /**
     * Copy directory safely (skip symbolic links)
     */
    private function copyDirectorySafe($source, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $targetPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            
            // Skip symbolic links
            if ($item->isLink()) {
                continue;
            }
            
            if ($item->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                // Ensure target directory exists
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                // Use copy function for regular files
                if (is_file($item->getPathname())) {
                    copy($item->getPathname(), $targetPath);
                }
            }
        }
    }

    /**
     * Create ZIP archive from backup directory
     */
    private function createZipArchive($sourcePath, $zipPath)
    {
        \Log::info("Creating ZIP archive", [
            'source_path' => $sourcePath,
            'zip_path' => $zipPath
        ]);
        
        $zip = new \ZipArchive();
        $zipFile = storage_path('app/' . $zipPath);
        
        \Log::info("ZIP file path", ['zip_file' => $zipFile]);
        
        // Ensure directory exists
        $zipDir = dirname($zipFile);
        if (!is_dir($zipDir)) {
            mkdir($zipDir, 0755, true);
            \Log::info("Created ZIP directory", ['zip_dir' => $zipDir]);
        }
        
        $result = $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        if ($result !== TRUE) {
            \Log::error("Failed to open ZIP file", [
                'result' => $result,
                'zip_file' => $zipFile,
                'error_message' => $zip->getStatusString()
            ]);
            throw new \Exception("Cannot create ZIP file: {$zipFile} (Result: {$result})");
        }
        
        $sourceDir = storage_path('app/' . $sourcePath);
        \Log::info("Source directory", ['source_dir' => $sourceDir, 'exists' => is_dir($sourceDir)]);
        
        if (!is_dir($sourceDir)) {
            throw new \Exception("Source directory does not exist: {$sourceDir}");
        }
        
        $this->addDirectoryToZip($zip, $sourceDir, '');
        
        $zip->close();
        
        if (!file_exists($zipFile) || filesize($zipFile) === 0) {
            \Log::error("ZIP file was not created or is empty", [
                'zip_file' => $zipFile,
                'exists' => file_exists($zipFile),
                'size' => file_exists($zipFile) ? filesize($zipFile) : 0
            ]);
            throw new \Exception("ZIP file was not created or is empty");
        }
        
        \Log::info("ZIP file created successfully", [
            'zip_file' => $zipFile,
            'size' => filesize($zipFile)
        ]);
    }
    
    /**
     * Add directory to ZIP recursively
     */
    private function addDirectoryToZip($zip, $dir, $zipPath)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = $zipPath . substr($filePath, strlen($dir) + 1);
            
            // Normalize path separators for cross-platform compatibility
            $relativePath = str_replace('\\', '/', $relativePath);
            
            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
    private function createBackupInfo($backupPath, $backupName, $includeFiles, $includeDatabase = true)
    {
        $info = [
            'name' => $backupName,
            'created_at' => Carbon::now()->setTimezone(config('app.timezone', 'UTC'))->toISOString(),
            'include_files' => $includeFiles,
            'include_database' => $includeDatabase,
            'database_type' => config('database.default'),
            'app_version' => config('app.version', '1.0.0'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        $infoFilePath = storage_path('app/' . $backupPath . '/backup_info.json');
        file_put_contents($infoFilePath, json_encode($info, JSON_PRETTY_PRINT));
    }

    /**
     * Get directory size
     */
    private function getDirectorySize($directory)
    {
        try {
            $size = 0;
            
            if (!is_dir($directory)) {
                return '0 B';
            }
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            return $this->formatBytes($size);
        } catch (\Exception $e) {
            \Log::error('Error calculating directory size: ' . $e->getMessage());
            return 'Unknown';
        }
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