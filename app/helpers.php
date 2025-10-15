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

if (!function_exists('getSafeErrorMessage')) {
    /**
     * Get safe error message based on debug mode
     * 
     * @param string $debugMessage The detailed error message for debug mode
     * @param string $safeMessage The safe error message for production
     * @param string|null $logContext Additional context for logging
     * @return string
     */
    function getSafeErrorMessage(string $debugMessage, string $safeMessage, ?string $logContext = null): string
    {
        // Log the detailed error for debugging purposes
        if ($logContext) {
            \Log::error("Error in {$logContext}: {$debugMessage}");
        } else {
            \Log::error("Application Error: {$debugMessage}");
        }

        // Return detailed message only in debug mode
        if (config('app.debug', false)) {
            return $debugMessage;
        }

        return $safeMessage;
    }
}

if (!function_exists('getSafeApiErrorResponse')) {
    /**
     * Get safe API error response based on debug mode
     * 
     * @param \Exception $exception The exception that occurred
     * @param string $safeMessage The safe error message for production
     * @param string|null $logContext Additional context for logging
     * @param int $statusCode HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    function getSafeApiErrorResponse(\Exception $exception, string $safeMessage, ?string $logContext = null, int $statusCode = 500): \Illuminate\Http\JsonResponse
    {
        $debugMessage = $exception->getMessage();
        
        // Log the detailed error
        if ($logContext) {
            \Log::error("API Error in {$logContext}: {$debugMessage}", [
                'exception' => $exception->getTraceAsString(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        } else {
            \Log::error("API Error: {$debugMessage}", [
                'exception' => $exception->getTraceAsString(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        }

        $response = [
            'success' => false,
            'message' => config('app.debug', false) ? $debugMessage : $safeMessage
        ];

        // Include additional debug info only in debug mode
        if (config('app.debug', false)) {
            $response['debug'] = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }

        return response()->json($response, $statusCode);
    }
}

if (!function_exists('getSafeWebErrorResponse')) {
    /**
     * Get safe web error response based on debug mode
     * 
     * @param \Exception $exception The exception that occurred
     * @param string $safeMessage The safe error message for production
     * @param string|null $logContext Additional context for logging
     * @return \Illuminate\Http\RedirectResponse
     */
    function getSafeWebErrorResponse(\Exception $exception, string $safeMessage, ?string $logContext = null): \Illuminate\Http\RedirectResponse
    {
        $debugMessage = $exception->getMessage();
        
        // Log the detailed error
        if ($logContext) {
            \Log::error("Web Error in {$logContext}: {$debugMessage}", [
                'exception' => $exception->getTraceAsString(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        } else {
            \Log::error("Web Error: {$debugMessage}", [
                'exception' => $exception->getTraceAsString(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        }

        $message = config('app.debug', false) ? $debugMessage : $safeMessage;
        
        return redirect()->back()->with('error', $message)->withInput();
    }
}