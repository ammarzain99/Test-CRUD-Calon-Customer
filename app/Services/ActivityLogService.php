<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Create a new class instance.
     */
    public static function log($action, $model, $modelId, $old = null, $new = null)
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'model'      => $model,
            'model_id'   => $modelId,
            'old_values' => $old,
            'new_values' => $new,
            'description'=> self::makeDescription($action, $model, $modelId),
            'ip_address' => request()->ip(),
        ]);
    }

    protected static function makeDescription($action, $model, $modelId)
    {
        $user = auth()->user();

        if (!$user) {
            return "Guest {$action} {$model}";
        }

        if ($modelId) {
            return "{$user->name} {$action} {$model} #{$modelId}";
        }

        return "{$user->name} {$action} {$model}";
    }
}
