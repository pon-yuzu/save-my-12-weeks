<?php

namespace App\Http\Controllers;

use App\Models\MailSubscription;
use App\Models\UnsubscribeReason;
use Illuminate\Http\Request;

class UnsubscribeController extends Controller
{
    public function show(string $token)
    {
        $subscription = MailSubscription::where('token', $token)->firstOrFail();

        if (!$subscription->is_active) {
            return redirect()->route('unsubscribe.complete');
        }

        return view('unsubscribe.show', compact('subscription'));
    }

    public function process(Request $request, string $token)
    {
        $subscription = MailSubscription::where('token', $token)->firstOrFail();

        if (!$subscription->is_active) {
            return redirect()->route('unsubscribe.complete');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:5000'],
        ], [
            'reason.required' => '入力してから送信してください。',
        ]);

        // 配信停止理由を記録
        UnsubscribeReason::create([
            'subscription_id' => $subscription->id,
            'reason' => $validated['reason'],
            'unsubscribed_at' => now(),
        ]);

        // サブスクリプションを停止
        $subscription->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);

        return redirect()->route('unsubscribe.complete');
    }

    public function complete()
    {
        return view('unsubscribe.complete');
    }
}
