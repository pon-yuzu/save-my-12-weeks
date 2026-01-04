<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'body',
        'target_type',
        'target_filter',
        'recipient_ids',
        'status',
        'scheduled_at',
        'sent_at',
        'sent_count',
        'opened_count',
    ];

    protected function casts(): array
    {
        return [
            'target_filter' => 'array',
            'recipient_ids' => 'array',
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(BroadcastRecipient::class);
    }

    /**
     * 送信対象の購読者を取得
     */
    public function getTargetSubscriptions()
    {
        $query = MailSubscription::where('is_active', true);

        switch ($this->target_type) {
            case 'individual':
                if (!empty($this->recipient_ids)) {
                    $query->whereIn('id', $this->recipient_ids);
                }
                break;

            case 'filtered':
                if (!empty($this->target_filter)) {
                    $filter = $this->target_filter;

                    // Day数でフィルター
                    if (isset($filter['day_min'])) {
                        $query->where('current_day', '>=', $filter['day_min']);
                    }
                    if (isset($filter['day_max'])) {
                        $query->where('current_day', '<=', $filter['day_max']);
                    }

                    // 登録日でフィルター
                    if (isset($filter['subscribed_after'])) {
                        $query->where('subscribed_at', '>=', $filter['subscribed_after']);
                    }
                    if (isset($filter['subscribed_before'])) {
                        $query->where('subscribed_at', '<=', $filter['subscribed_before']);
                    }
                }
                break;

            case 'all':
            default:
                // 全員
                break;
        }

        return $query;
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now());
    }
}
