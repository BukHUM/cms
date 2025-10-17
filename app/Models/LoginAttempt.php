<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'core_login_attempts';
    
    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'success',
        'failure_reason',
    ];

    protected $casts = [
        'success' => 'boolean',
    ];
}
