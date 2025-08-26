<?php

namespace App\Services;

use App\Models\UserActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function logActivity(
        int $userId,
        string $actionType,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): UserActivityLog {
        return UserActivityLog::log(
            $userId,
            $actionType,
            $description,
            $model ? get_class($model) : null,
            $model ? $model->id : null,
            $oldValues,
            $newValues
        );
    }

    public function logLogin(int $userId): UserActivityLog
    {
        return $this->logActivity($userId, 'login', 'User logged in');
    }

    public function logLogout(int $userId): UserActivityLog
    {
        return $this->logActivity($userId, 'logout', 'User logged out');
    }

    public function logCreate(int $userId, Model $model, array $newValues = []): UserActivityLog
    {
        $modelName = class_basename($model);
        return $this->logActivity(
            $userId,
            'create',
            "Created {$modelName} #{$model->id}",
            $model,
            null,
            $newValues ?: $model->toArray()
        );
    }

    public function logUpdate(int $userId, Model $model, array $oldValues, array $newValues): UserActivityLog
    {
        $modelName = class_basename($model);
        return $this->logActivity(
            $userId,
            'update',
            "Updated {$modelName} #{$model->id}",
            $model,
            $oldValues,
            $newValues
        );
    }

    public function logDelete(int $userId, Model $model): UserActivityLog
    {
        $modelName = class_basename($model);
        return $this->logActivity(
            $userId,
            'delete',
            "Deleted {$modelName} #{$model->id}",
            $model,
            $model->toArray(),
            null
        );
    }

    public function logRestore(int $userId, Model $model): UserActivityLog
    {
        $modelName = class_basename($model);
        return $this->logActivity(
            $userId,
            'restore',
            "Restored {$modelName} #{$model->id}",
            $model
        );
    }

    public function logCustomAction(int $userId, string $action, string $description, ?Model $model = null): UserActivityLog
    {
        return $this->logActivity($userId, $action, $description, $model);
    }
}
