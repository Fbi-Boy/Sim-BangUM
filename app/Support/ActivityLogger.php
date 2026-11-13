<?php
namespace App\Support;
use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(string $action, $subject = null, ?string $description = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        ActivityLog::create([
            "actor_user_id" => auth()->id(),
            "action" => $action,
            "subject_type" => $subject ? get_class($subject) : null,
            "subject_id" => $subject?->id,
            "description" => $description,
            "old_values" => $oldValues,
            "new_values" => $newValues,
            "ip_address" => request()->ip(),
            "user_agent" => substr(request()->userAgent() ?? "", 0, 500),
        ]);
    }
}
