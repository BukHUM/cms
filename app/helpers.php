<?php

if (!function_exists('getCurrentAdminUser')) {
    /**
     * Get current admin user data from session
     */
    function getCurrentAdminUser(): ?object
    {
        if (!session('admin_logged_in')) {
            return null;
        }

        return (object) [
            'id' => session('admin_user_id'),
            'name' => session('admin_user_name'),
            'email' => session('admin_user_email'),
            'role' => session('admin_user_role'),
        ];
    }
}

if (!function_exists('getCurrentAdminUserName')) {
    /**
     * Get current admin user name
     */
    function getCurrentAdminUserName(): string
    {
        $user = getCurrentAdminUser();
        return $user && $user->name ? $user->name : 'Admin';
    }
}

if (!function_exists('getCurrentAdminUserRole')) {
    /**
     * Get current admin user role display name
     */
    function getCurrentAdminUserRole(): string
    {
        $user = getCurrentAdminUser();
        if (!$user || !$user->role) {
            return 'Administrator';
        }

        return match($user->role) {
            'admin' => 'Administrator',
            'moderator' => 'Moderator',
            'user' => 'User',
            default => 'Administrator'
        };
    }
}
