<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif; line-height: 1.8; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #faf8f5; padding: 30px; border-radius: 8px;">
        <h2 style="color: #0d7377; margin-bottom: 20px;">30日講座へようこそ！</h2>

        <p style="margin-bottom: 20px;">ご登録ありがとうございます。</p>

        <p style="margin-bottom: 20px;">
            これから30日間、あなたの「12週間を取り戻す」ためのヒントをお届けします。<br>
            毎日1通、短いメールが届きます。
        </p>

        @if($subscription->diagnosisResult?->wheel_image_path)
        <div style="background: #fff; border-left: 4px solid #43aa8b; padding: 16px 20px; margin: 24px 0;">
            <p style="margin: 0; font-weight: 500;">🎨 あなたのライフホイール</p>
            <p style="margin: 8px 0 0 0; color: #666; font-size: 0.9rem;">このメールに画像を添付しました。大切に保存してくださいね。</p>
        </div>
        @endif

        <div style="background: #fff; border-left: 4px solid #0d7377; padding: 16px 20px; margin: 24px 0;">
            <p style="margin: 0; font-weight: 500;">⏰ 配信時間を選ぶ</p>
            <p style="margin: 8px 0 0 0; color: #666; font-size: 0.9rem;">
                現在の配信時間は<strong>朝8時</strong>です。<br>
                お好みの時間に変更できます。
            </p>
            <p style="margin: 12px 0 0 0;">
                <a href="{{ $settingsUrl }}" style="display: inline-block; background: #0d7377; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 0.9rem;">配信時間を選ぶ</a>
            </p>
        </div>

        <div style="background: #fff; border-left: 4px solid #ffb347; padding: 16px 20px; margin: 24px 0;">
            <p style="margin: 0; font-weight: 500;">📬 明日から配信スタート！</p>
            <p style="margin: 8px 0 0 0; color: #666; font-size: 0.9rem;">楽しみにお待ちください。</p>
        </div>

        <p style="margin-bottom: 20px;">
            Sayaka
        </p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0d8cc; font-size: 0.85rem; color: #666;">
            <p style="margin: 5px 0;">━━━━━━━━━━━━━━━</p>
            <p style="margin: 5px 0;"><strong>Save My 12 Weeks</strong>｜私の12週間を取り戻せ</p>
            <p style="margin: 5px 0;">主催：Sayaka</p>
            <p style="margin: 10px 0;">━━━━━━━━━━━━━━━</p>

            <p style="margin: 10px 0;">▼ 無料セミナーに参加する（オプチャで仲間と繋がれます）</p>
            <p style="margin: 5px 0;"><a href="{{ config('app.seminar_url') }}" style="color: #0d7377;">{{ config('app.seminar_url') }}</a></p>

            <p style="margin: 10px 0;">▼ 公式LINE</p>
            <p style="margin: 5px 0;"><a href="{{ config('app.line_url') }}" style="color: #0d7377;">{{ config('app.line_url') }}</a></p>

            <p style="margin: 10px 0;">▼ note / YouTube</p>
            <p style="margin: 5px 0;"><a href="https://note.com/ponglish_in_aus" style="color: #0d7377;">note.com/ponglish_in_aus</a></p>
            <p style="margin: 5px 0;"><a href="https://www.youtube.com/@geek-out-with-me" style="color: #0d7377;">youtube.com/@geek-out-with-me</a></p>

            <p style="margin: 15px 0; padding: 10px; background: #f0ebe3; border-radius: 4px; font-size: 0.8rem;">
                ※ このメールは配信専用です。ご返信いただいてもお答えできません。<br>
                お問い合わせは公式LINE、またはSNSのDMよりお願いします。
            </p>

            <p style="margin-top: 16px;">
                <a href="{{ route('unsubscribe.show', $subscription->token) }}" style="color: #999; font-size: 0.8rem;">配信停止はこちら</a>
            </p>
        </div>
    </div>
</body>
</html>
