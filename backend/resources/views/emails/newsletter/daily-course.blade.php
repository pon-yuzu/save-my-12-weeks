<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif; line-height: 1.8; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #faf8f5; padding: 30px; border-radius: 8px;">
        <div style="font-size: 0.85rem; color: #666; margin-bottom: 16px;">
            Day {{ $template->day_number }} / 30
        </div>

        <div style="line-height: 2;">
            {!! $personalizedBody !!}
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0d8cc; font-size: 0.85rem; color: #666;">
            <p style="margin: 5px 0;">━━━━━━━━━━━━━━━</p>
            <p style="margin: 5px 0;">Save My 12 Weeks｜私の12週間を取り戻せ</p>
            <p style="margin: 5px 0;">主催：Sayaka</p>
            <p style="margin: 5px 0;">━━━━━━━━━━━━━━━</p>
            <p style="margin-top: 16px;">
                <a href="{{ $unsubscribeUrl }}" style="color: #666;">配信停止はこちら</a>
            </p>
        </div>
    </div>
    @if($trackingPixelUrl)
    <img src="{{ $trackingPixelUrl }}" alt="" width="1" height="1" style="display:none;border:0;" />
    @endif
</body>
</html>
