<?php

// Test file to check production environment
// Access via: https://cms.tonkla.co/test.php

echo "<h1>Production Environment Test</h1>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";

// Test 2: Laravel Environment
echo "<h2>2. Laravel Environment</h2>";
$root_dir = dirname(__DIR__);
if (file_exists($root_dir . '/vendor/autoload.php')) {
    require_once $root_dir . '/vendor/autoload.php';
    
    try {
        $app = require_once $root_dir . '/bootstrap/app.php';
        echo "Laravel App: " . get_class($app) . "<br>";
        
        // Try to get environment safely
        try {
            $env = $app->environment();
            echo "Environment: " . $env . "<br>";
        } catch (Exception $e) {
            echo "Environment: Error - " . $e->getMessage() . "<br>";
        }
        
        // Try to check debug mode safely
        try {
            $debug = $app->environment('local');
            echo "Debug Mode: " . ($debug ? 'Local' : 'Production') . "<br>";
        } catch (Exception $e) {
            echo "Debug Mode: Error - " . $e->getMessage() . "<br>";
        }
        
    } catch (Exception $e) {
        echo "Laravel Error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "Laravel not found<br>";
}

// Test 3: Database Connection
echo "<h2>3. Database Connection</h2>";
try {
    if (file_exists($root_dir . '/.env')) {
        $env = file_get_contents($root_dir . '/.env');
        echo "Environment file exists<br>";
        
        // Parse DB config
        preg_match('/DB_CONNECTION=(.+)/', $env, $matches);
        $db_connection = isset($matches[1]) ? trim($matches[1]) : 'unknown';
        echo "DB Connection: " . $db_connection . "<br>";
        
        preg_match('/DB_HOST=(.+)/', $env, $matches);
        $db_host = isset($matches[1]) ? trim($matches[1]) : 'unknown';
        echo "DB Host: " . $db_host . "<br>";
        
        preg_match('/DB_DATABASE=(.+)/', $env, $matches);
        $db_database = isset($matches[1]) ? trim($matches[1]) : 'unknown';
        echo "DB Database: " . $db_database . "<br>";
    } else {
        echo "Environment file not found<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

// Test 4: File Permissions
echo "<h2>4. File Permissions</h2>";
$directories = ['storage', 'bootstrap/cache', 'public'];
foreach ($directories as $dir) {
    $full_path = $root_dir . '/' . $dir;
    if (is_dir($full_path)) {
        $perms = substr(sprintf('%o', fileperms($full_path)), -4);
        echo "Directory $dir: " . $perms . " " . (is_writable($full_path) ? "(writable)" : "(not writable)") . "<br>";
    } else {
        echo "Directory $dir: not found<br>";
    }
}

// Test 5: Middleware Files
echo "<h2>5. Middleware Files</h2>";
$middleware_files = [
    'app/Http/Middleware/CheckMaintenanceMode.php',
    'app/Http/Middleware/CheckDebugMode.php'
];

foreach ($middleware_files as $file) {
    $full_path = $root_dir . '/' . $file;
    if (file_exists($full_path)) {
        echo "File $file: exists<br>";
        $content = file_get_contents($full_path);
        if (strpos($content, 'Setting::get') !== false) {
            echo "  - Contains Setting::get<br>";
        } else {
            echo "  - Does not contain Setting::get<br>";
        }
    } else {
        echo "File $file: not found<br>";
    }
}

echo "<h2>Test Complete</h2>";
echo "<p>If you see this page, PHP is working correctly.</p>";
