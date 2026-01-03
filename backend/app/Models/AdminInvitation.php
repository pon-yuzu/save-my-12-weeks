<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AdminInvitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'role',
        'invited_by',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public const ROLE_ADMIN = 'admin';
    public const ROLE_EDITOR = 'editor';

    public const ROLES = [
        self::ROLE_ADMIN => '管理者',
        self::ROLE_EDITOR => '編集者',
    ];

    /**
     * 招待を作成
     */
    public static function createInvitation(int $invitedBy, string $role, ?string $email = null, int $expiresInDays = 7): self
    {
        return self::create([
            'email' => $email,
            'token' => Str::random(64),
            'role' => $role,
            'invited_by' => $invitedBy,
            'expires_at' => now()->addDays($expiresInDays),
        ]);
    }

    /**
     * 招待者
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'invited_by');
    }

    /**
     * 有効な招待かどうか
     */
    public function isValid(): bool
    {
        return !$this->used_at && $this->expires_at->isFuture();
    }

    /**
     * 招待を使用済みにする
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * 招待リンクを取得
     */
    public function getInviteUrlAttribute(): string
    {
        return url("/admin/register/{$this->token}");
    }

    /**
     * 権限ラベル
     */
    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /**
     * ステータスラベル
     */
    public function getStatusAttribute(): string
    {
        if ($this->used_at) {
            return '使用済み';
        }
        if ($this->expires_at->isPast()) {
            return '期限切れ';
        }
        return '有効';
    }

    /**
     * ステータスのバッジクラス
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->used_at) {
            return 'badge-warning';
        }
        if ($this->expires_at->isPast()) {
            return 'badge-danger';
        }
        return 'badge-success';
    }
}
