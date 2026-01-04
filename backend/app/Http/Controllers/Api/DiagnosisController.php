<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterWelcome;
use App\Models\DiagnosisResult;
use App\Models\MailSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            'wheel_image_base64' => ['nullable', 'string'],
        ]);

        // ホイール画像の保存
        $wheelImagePath = null;
        if (!empty($validated['wheel_image_base64'])) {
            $wheelImagePath = $this->saveWheelImage($validated['wheel_image_base64']);
        }

        // 診断結果の保存
        $diagnosis = DiagnosisResult::create([
            'health_score' => $validated['health_score'],
            'mind_score' => $validated['mind_score'],
            'money_score' => $validated['money_score'],
            'career_score' => $validated['career_score'],
            'time_score' => $validated['time_score'],
            'living_score' => $validated['living_score'],
            'relationships_score' => $validated['relationships_score'],
            'vision_score' => $validated['vision_score'],
            'selected_areas' => $validated['selected_areas'] ?? null,
            'free_text' => $validated['free_text'] ?? null,
            'wheel_image_path' => $wheelImagePath,
        ]);

        return response()->json([
            'success' => true,
            'diagnosis_id' => $diagnosis->id,
        ]);
    }

    /**
     * Base64画像をファイルとして保存
     */
    private function saveWheelImage(string $base64Image): string
    {
        // Base64デコード
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $imageData = base64_decode($imageData);

        // ファイル名生成
        $filename = 'wheel_' . uniqid() . '_' . time() . '.png';
        $path = 'wheel_images/' . $filename;

        // Storage に保存
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'diagnosis_id' => ['nullable', 'exists:diagnosis_results,id'],
        ]);

        // 既存の登録をチェック
        $existing = MailSubscription::where('email', $validated['email'])->first();

        if ($existing) {
            if ($existing->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'すでに登録されています。',
                ], 422);
            }

            // 再登録の場合（配信時間はデフォルト値にリセット）
            $existing->update([
                'is_active' => true,
                'current_day' => 1,
                'preferred_time' => MailSubscription::DEFAULT_PREFERRED_TIME,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            $subscription = $existing;
        } else {
            // 新規登録（配信時間はデフォルト値で作成）
            $subscription = MailSubscription::create([
                'email' => $validated['email'],
                'preferred_time' => MailSubscription::DEFAULT_PREFERRED_TIME,
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
