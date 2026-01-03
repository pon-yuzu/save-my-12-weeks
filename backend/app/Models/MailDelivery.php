<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'template_id',
        'sent_at',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
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
}
