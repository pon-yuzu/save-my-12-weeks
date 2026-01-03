<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeminarSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public const KEYS = [
        'schedule' => '日程',
        'zoom_link' => 'Zoom参加リンク',
        'line_openchat_link' => 'LINEオープンチャットリンク',
        'participation_code' => '参加コード',
        'guidance_text' => '案内テキスト',
    ];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        return Cache::remember("seminar_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting?->value ?? $default;
        });
    }

    public static function setValue(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("seminar_setting_{$key}");
    }

    public static function getAll(): array
    {
        return Cache::remember('seminar_settings_all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    public static function clearCache(): void
    {
        foreach (array_keys(self::KEYS) as $key) {
            Cache::forget("seminar_setting_{$key}");
        }
        Cache::forget('seminar_settings_all');
    }
}
