<?php

// Test file to check user permissions
// Access via: https://cms.tonkla.co/check-permissions.php

echo "<h1>User Permissions Check</h1>";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    try {
        // Get first user
        $user = \App\Models\User::first();
        
        if ($user) {
            echo "<h2>User Information</h2>";
            echo "Email: " . $user->email . "<br>";
            echo "ID: " . $user->id . "<br>";
            
            echo "<h2>Roles</h2>";
            $roles = $user->roles;
            if ($roles->count() > 0) {
                foreach ($roles as $role) {
                    echo "- " . $role->name . "<br>";
                }
            } else {
                echo "No roles assigned<br>";
            }
            
            echo "<h2>Permissions</h2>";
            $permissions = $user->permissions;
            if ($permissions->count() > 0) {
                foreach ($permissions as $permission) {
                    echo "- " . $permission->name . "<br>";
                }
            } else {
                echo "No direct permissions assigned<br>";
            }
            
            echo "<h2>All Permissions (including from roles)</h2>";
            $allPermissions = $user->getAllPermissions();
            if ($allPermissions->count() > 0) {
                foreach ($allPermissions as $permission) {
                    echo "- " . $permission->name . "<br>";
                }
            } else {
                echo "No permissions found<br>";
            }
            
            echo "<h2>Settings Permission Check</h2>";
            $hasSettingsView = $user->hasPermission('settings.view');
            $hasSettingsEdit = $user->hasPermission('settings.edit');
            $hasSettingsCreate = $user->hasPermission('settings.create');
            $hasSettingsDelete = $user->hasPermission('settings.delete');
            
            echo "settings.view: " . ($hasSettingsView ? 'YES' : 'NO') . "<br>";
            echo "settings.edit: " . ($hasSettingsEdit ? 'YES' : 'NO') . "<br>";
            echo "settings.create: " . ($hasSettingsCreate ? 'YES' : 'NO') . "<br>";
            echo "settings.delete: " . ($hasSettingsDelete ? 'YES' : 'NO') . "<br>";
            
            echo "<h2>Admin Role Check</h2>";
            $isAdmin = $user->hasRole('admin');
            echo "Is Admin: " . ($isAdmin ? 'YES' : 'NO') . "<br>";
            
        } else {
            echo "No users found in database<br>";
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
    }
} else {
    echo "Laravel not found<br>";
}

echo "<h2>Test Complete</h2>";
