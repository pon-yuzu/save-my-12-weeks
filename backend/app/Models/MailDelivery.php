<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MailDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'template_id',
        'sent_at',
        'status',
        'error_message',
        'tracking_token',
        'opened_at',
        'open_count',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'opened_at' => 'datetime',
            'open_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MailDelivery $delivery) {
            if (empty($delivery->tracking_token)) {
                $delivery->tracking_token = Str::random(64);
            }
        });
    }

    /**
     * 開封を記録
     */
    public function recordOpen(): void
    {
        if ($this->opened_at === null) {
            $this->opened_at = now();
        }
        $this->increment('open_count');
    }

    /**
     * 開封済みかどうか
     */
    public function isOpened(): bool
    {
        return $this->opened_at !== null;
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(MailSubscription::class, 'subscription_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MailTemplate::class, 'template_id');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeOpened($query)
    {
        return $query->whereNotNull('opened_at');
    }

    public function scopeNotOpened($query)
    {
        return $query->whereNull('opened_at');
    }
}
