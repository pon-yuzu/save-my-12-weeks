<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterWelcome;
use App\Models\DiagnosisResult;
use App\Models\MailSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DiagnosisController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'health_score' => ['required', 'integer', 'min:1', 'max:10'],
            'mind_score' => ['required', 'integer', 'min:1', 'max:10'],
            'money_score' => ['required', 'integer', 'min:1', 'max:10'],
            'career_score' => ['required', 'integer', 'min:1', 'max:10'],
            'time_score' => ['required', 'integer', 'min:1', 'max:10'],
            'living_score' => ['required', 'integer', 'min:1', 'max:10'],
            'relationships_score' => ['required', 'integer', 'min:1', 'max:10'],
            'vision_score' => ['required', 'integer', 'min:1', 'max:10'],
            'selected_areas' => ['nullable', 'array'],
            'free_text' => ['nullable', 'string', 'max:2000'],
        ]);

        $diagnosis = DiagnosisResult::create($validated);

        return response()->json([
            'success' => true,
            'diagnosis_id' => $diagnosis->id,
        ]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'preferred_time' => ['nullable', 'date_format:H:i', 'in:06:00,07:00,08:00,12:00,18:00,20:00'],
            'diagnosis_id' => ['nullable', 'exists:diagnosis_results,id'],
        ]);

        $preferredTime = $validated['preferred_time'] ?? MailSubscription::DEFAULT_PREFERRED_TIME;

        // 既存の登録をチェック
        $existing = MailSubscription::where('email', $validated['email'])->first();

        if ($existing) {
            if ($existing->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'すでに登録されています。',
                ], 422);
            }

            // 再登録の場合
            $existing->update([
                'is_active' => true,
                'current_day' => 1,
                'preferred_time' => $preferredTime,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            $subscription = $existing;
        } else {
            $subscription = MailSubscription::create([
                'email' => $validated['email'],
                'preferred_time' => $preferredTime,
            ]);
        }

        // 診断結果と紐付け
        if (!empty($validated['diagnosis_id'])) {
            DiagnosisResult::where('id', $validated['diagnosis_id'])
                ->whereNull('subscription_id')
                ->update(['subscription_id' => $subscription->id]);
        }

        // ウェルカムメール送信
        Mail::to($subscription->email)->queue(new NewsletterWelcome($subscription));

        return response()->json([
            'success' => true,
            'message' => '登録が完了しました。',
        ]);
    }
}
