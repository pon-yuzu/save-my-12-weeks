<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif; line-height: 1.8; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #faf8f5; padding: 30px; border-radius: 8px;">
        <div style="line-height: 2;">
            {!! nl2br(e($personalizedBody)) !!}
        </div>

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
            <p style="margin: 5px 0;"><a href="https://www.youtube.com/@cairsen1000" style="color: #0d7377;">youtube.com/@cairsen1000</a></p>

            <p style="margin: 15px 0; padding: 10px; background: #e8f4f3; border-radius: 4px; font-size: 0.8rem;">
                💬 ご感想・ご質問・個別相談のご希望など、<br>
                このメールに返信していただければ直接届きます！
            </p>

            <p style="margin-top: 16px;">
                <a href="{{ $unsubscribeUrl }}" style="color: #999; font-size: 0.8rem;">配信停止はこちら</a>
            </p>
        </div>
    </div>
    @if($trackingPixelUrl)
    <img src="{{ $trackingPixelUrl }}" alt="" width="1" height="1" style="display:none;border:0;" />
    @endif
</body>
</html>
