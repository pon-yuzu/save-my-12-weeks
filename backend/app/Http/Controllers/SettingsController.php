<?php

namespace App\Http\Controllers;

use App\Models\MailSubscription;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * 配信時間設定ページを表示
     */
    public function showTime(string $token)
    {
        $subscription = MailSubscription::where('settings_token', $token)->firstOrFail();

        if (!$subscription->is_active) {
            return redirect()->route('diagnosis')
                ->with('error', 'このリンクは無効になっています。');
        }

        return view('settings.time', [
            'subscription' => $subscription,
            'preferredTimes' => MailSubscription::PREFERRED_TIMES,
        ]);
    }

    /**
     * 配信時間を更新
     */
    public function updateTime(Request $request, string $token)
    {
        $subscription = MailSubscription::where('settings_token', $token)->firstOrFail();

        if (!$subscription->is_active) {
            return redirect()->route('diagnosis')
                ->with('error', 'このリンクは無効になっています。');
        }

        $validated = $request->validate([
            'preferred_time' => ['required', 'string', 'in:' . implode(',', array_keys(MailSubscription::PREFERRED_TIMES))],
        ], [
            'preferred_time.required' => '配信時間を選択してください。',
            'preferred_time.in' => '無効な配信時間です。',
        ]);

        $subscription->update([
            'preferred_time' => $validated['preferred_time'],
        ]);

        return redirect()->route('settings.time.complete');
    }

    /**
     * 設定完了ページ
     */
    public function complete()
    {
        return view('settings.complete');
    }
}
