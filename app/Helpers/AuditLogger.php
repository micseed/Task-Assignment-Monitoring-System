<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Log a user action to the database.
     *
     * @param string $action
     * @param string|null $description
     * @param int|null $userId
     * @return AuditLog
     */
    public static function log(string $action, ?string $description = null, ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id'     => $userId ?? Auth::id(),
            'action'      => $action,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}
