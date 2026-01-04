<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'scheduled_at',
        'duration_minutes',
        'zoom_link',
        'line_openchat_link',
        'participation_code',
        'description',
        'is_active',
        'capacity',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
        'capacity' => 'integer',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(SeminarApplication::class);
    }

    /**
     * 直近の開催予定セミナーを取得
     */
    public static function upcoming()
    {
        return static::where('is_active', true)
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->first();
    }

    /**
     * FullCalendar用のイベント形式に変換
     */
    public function toCalendarEvent(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->scheduled_at->toIso8601String(),
            'end' => $this->scheduled_at->addMinutes($this->duration_minutes)->toIso8601String(),
            'backgroundColor' => $this->is_active ? '#0d7377' : '#9a9a9a',
            'borderColor' => $this->is_active ? '#0d7377' : '#9a9a9a',
            'extendedProps' => [
                'zoom_link' => $this->zoom_link,
                'line_openchat_link' => $this->line_openchat_link,
                'participation_code' => $this->participation_code,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'capacity' => $this->capacity,
                'applications_count' => $this->applications()->count(),
            ],
        ];
    }

    /**
     * 日程を表示用フォーマットで取得
     */
    public function getFormattedScheduleAttribute(): string
    {
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        $weekday = $weekdays[$this->scheduled_at->dayOfWeek];

        return $this->scheduled_at->format('Y年n月j日') . "（{$weekday}）" . $this->scheduled_at->format('H:i') . '〜';
    }

    /**
     * 申込人数を取得
     */
    public function getApplicationsCountAttribute(): int
    {
        return $this->applications()->count();
    }

    /**
     * 満席かどうか
     */
    public function getIsFullAttribute(): bool
    {
        if ($this->capacity === null) {
            return false;
        }
        return $this->applications_count >= $this->capacity;
    }
}
