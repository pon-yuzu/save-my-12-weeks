<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif; line-height: 1.8; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #faf8f5; padding: 30px; border-radius: 8px;">
        <p style="margin-bottom: 20px;">{{ $application->name }}さん</p>

        @if($type === '1day')
        {{-- 前日リマインド --}}
        <p style="margin-bottom: 20px; font-size: 1.2em; font-weight: bold; color: #0d7377;">
            明日はセミナー当日です！
        </p>

        <p style="margin-bottom: 20px;">
            お申込みいただいた「Save My 12 Weeks」セミナーが、いよいよ明日に迫りました。
        </p>

        <p style="margin-bottom: 20px;">
            12週間で人生を変えるヒント、一緒に見つけましょう。<br>
            明日お会いできるのを楽しみにしています！
        </p>

        @else
        {{-- 1時間前リマインド --}}
        <p style="margin-bottom: 20px; font-size: 1.2em; font-weight: bold; color: #ff6b35;">
            まもなくセミナーが始まります！
        </p>

        <p style="margin-bottom: 20px;">
            開始まであと約1時間です。<br>
            準備はできていますか？
        </p>

        <p style="margin-bottom: 20px;">
            静かな場所で、メモを取れる状態でご参加ください。<br>
            あなたの12週間が、今日から動き出します。
        </p>
        @endif

        <div style="background: #fff; border: 1px solid #e0d8cc; padding: 20px; margin: 30px 0; border-radius: 8px;">
            <p style="margin: 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
            <p style="margin: 10px 0;">📅 日時：{{ $seminar->formatted_schedule }}</p>
            <p style="margin: 10px 0;">💻 開催：オンライン（Zoom）</p>
            <p style="margin: 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
        </div>

        @if($seminar->zoom_link)
        <div style="background: #0d7377; color: #fff; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
            <p style="margin: 0 0 15px 0; font-weight: bold;">▼ Zoom参加リンク</p>
            <a href="{{ $seminar->zoom_link }}" style="display: inline-block; background: #fff; color: #0d7377; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                セミナーに参加する
            </a>
        </div>
        @endif

        @if($seminar->line_openchat_link)
        <div style="background: #f5f2ed; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0; font-weight: bold;">💬 LINEオープンチャット</p>
            <p style="margin: 0;">
                まだ参加していない方は、こちらからご参加ください：<br>
                <a href="{{ $seminar->line_openchat_link }}" style="color: #0d7377;">{{ $seminar->line_openchat_link }}</a>
            </p>
            @if($seminar->participation_code)
            <p style="margin: 10px 0 0 0;">
                参加コード：<strong>{{ $seminar->participation_code }}</strong>
            </p>
            @endif
        </div>
        @endif

        <div style="font-size: 0.85em; color: #666; margin-top: 30px; border-top: 1px solid #e0d8cc; padding-top: 20px;">
            <p style="margin: 5px 0;">※ 開始5分前にはZoomにアクセスしてお待ちください</p>
            <p style="margin: 5px 0;">※ カメラ・マイクはONでもOFFでもOKです</p>
        </div>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0d8cc; font-size: 0.9em; color: #666;">
            <p style="margin: 5px 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
            <p style="margin: 5px 0;">Save My 12 Weeks｜私の12週間を取り戻せ</p>
            <p style="margin: 5px 0;">主催：Sayaka</p>
            <p style="margin: 5px 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
        </div>
    </div>
</body>
</html>
