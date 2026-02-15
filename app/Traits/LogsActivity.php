<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        // CREATE
        static::created(function ($model) {
            self::logActivity($model, 'create', null, $model->getAttributes());
        });

        // UPDATE (only changed fields)
        static::updated(function ($model) {
            $old = [];
            $new = [];

            foreach ($model->getChanges() as $field => $value) {
                if ($field === 'updated_at') continue;

                $old[$field] = $model->getOriginal($field);
                $new[$field] = $value;
            }

            if (!empty($new)) {
                self::logActivity($model, 'update', $old, $new);
            }
        });

        // DELETE (soft delete supported)
        static::deleted(function ($model) {
            self::logActivity($model, 'delete', $model->getOriginal(), null);
        });
    }

    protected static function logActivity($model, $action, $oldValues = null, $newValues = null)
    {
        try {
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'model'      => class_basename($model),
                'model_id'   => (string) $model->getKey(),
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'description'=> self::makeDescription($model, $action),
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('ActivityLog failed: '.$e->getMessage());
        }
    }

    protected static function makeDescription($model, $action)
    {
        $user = Auth::user()?->name ?? 'System';

        return match ($action) {
            'create' => "{$user} membuat ".class_basename($model)." #{$model->getKey()}",
            'update' => "{$user} mengupdate ".class_basename($model)." #{$model->getKey()}",
            'delete' => "{$user} menghapus ".class_basename($model)." #{$model->getKey()}",
            default  => "{$user} {$action} ".class_basename($model)." #{$model->getKey()}",
        };
    }
}
