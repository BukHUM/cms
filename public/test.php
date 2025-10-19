<?php

// Test file to check production environment
// Access via: https://cms.tonkla.co/test.php

echo "<h1>Production Environment Test</h1>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";

// Test 2: Laravel Environment
echo "<h2>2. Laravel Environment</h2>";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "Laravel App: " . get_class($app) . "<br>";
    echo "Environment: " . $app->environment() . "<br>";
    echo "Debug Mode: " . ($app->environment('local') ? 'Local' : 'Production') . "<br>";
} else {
    echo "Laravel not found<br>";
}

// Test 3: Database Connection
echo "<h2>3. Database Connection</h2>";
try {
    if (file_exists(__DIR__ . '/.env')) {
        $env = file_get_contents(__DIR__ . '/.env');
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
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "Directory $dir: " . $perms . " " . (is_writable($dir) ? "(writable)" : "(not writable)") . "<br>";
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
    if (file_exists($file)) {
        echo "File $file: exists<br>";
        $content = file_get_contents($file);
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
