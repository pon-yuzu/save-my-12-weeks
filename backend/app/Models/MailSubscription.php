<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class MailSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'nickname',
        'token',
        'settings_token',
        'is_active',
        'current_day',
        'preferred_time',
        'subscribed_at',
        'unsubscribed_at',
    ];

    /**
     * 配信時間の選択肢
     * ※ routes/console.php のスケジュールと同期すること
     */
    public const PREFERRED_TIMES = [
        '06:00' => '朝6時（早起き派）',
        '07:00' => '朝7時（朝活派）',
        '08:00' => '朝8時（通勤前）',
        '12:00' => 'お昼12時（ランチタイム）',
        '18:00' => '夕方6時（仕事終わり）',
        '20:00' => '夜8時（寝る前）',
    ];

    public const DEFAULT_PREFERRED_TIME = '08:00';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'current_day' => 'integer',
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MailSubscription $subscription) {
            if (empty($subscription->token)) {
                $subscription->token = Str::random(64);
            }
            if (empty($subscription->settings_token)) {
                $subscription->settings_token = Str::random(64);
            }
            if (empty($subscription->subscribed_at)) {
                $subscription->subscribed_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(MailDelivery::class, 'subscription_id');
    }

    public function unsubscribeReason(): HasOne
    {
        return $this->hasOne(UnsubscribeReason::class, 'subscription_id');
    }

    public function diagnosisResult(): HasOne
    {
        return $this->hasOne(DiagnosisResult::class, 'subscription_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
