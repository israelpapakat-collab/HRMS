<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'url',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public static function createForUser(int $userId, string $type, string $title, string $message, ?string $url = null): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);
    }

    /**
     * Notify every user who holds any of the given role codes.
     */
    public static function createForRoles(array $roleCodes, string $type, string $title, string $message, ?string $url = null): int
    {
        $userIds = \App\Models\User::whereHas('roles', function ($q) use ($roleCodes) {
            $q->whereIn('code', $roleCodes);
        })->where('is_active', true)->pluck('id')->all();

        foreach ($userIds as $userId) {
            self::createForUser($userId, $type, $title, $message, $url);
        }

        return count($userIds);
    }
}
