<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif; line-height: 1.8; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #faf8f5; padding: 30px; border-radius: 8px;">
        <p style="margin-bottom: 20px;">セミナーへのお申込みありがとうございます！</p>

        <p style="margin-bottom: 20px;">さっきの質問の答え、何だと思いましたか？<br>「何かを始めるのに一番いいタイミング」</p>

        <p style="margin-bottom: 20px;">私ははじめてこの質問をされた時、「…2月？」と答えて、師匠に呆れられました。<br>「今」とか、「やりたいとき」って答える人の方が多いかもしれませんね。</p>

        <p style="margin-bottom: 20px;">答えは「昨日」です。<br>えーずる！って思いました？私もです。<br>でも、真理だと思います。</p>

        <p style="margin-bottom: 20px;">もしも過去に戻れるなら。<br>もっと早くはじめていたら。<br>もっと若い頃に知っていたら。</p>

        <p style="margin-bottom: 20px;">そんな風に思うことはたくさんあります。<br>だから、「昨日始めておけた」が、あったかもしれないベストタイミングなんです。</p>

        <p style="margin-bottom: 20px;">...でも昨日には戻れない。<br>だから、今日は次に一番いいタイミング。</p>

        <p style="margin-bottom: 20px;">このセミナーに申し込んだあなたは、もう動き出してます。<br>踏み出してくれて、ありがとう。</p>

        <div style="background: #fff; border: 1px solid #e0d8cc; padding: 20px; margin: 30px 0; border-radius: 4px;">
            <p style="margin: 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
            <p style="margin: 10px 0;">日程：{{ $settings['schedule'] ?? '（未定）' }}</p>
            <p style="margin: 10px 0;">開催：オンライン（Zoom）</p>
            <p style="margin: 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
        </div>

        <div style="background: #0d7377; color: #fff; padding: 20px; border-radius: 4px; margin: 20px 0;">
            <p style="margin: 0 0 15px 0; font-weight: bold;">▼ 次のステップ</p>
            <p style="margin: 0 0 10px 0;">オープンチャットに参加してください</p>
            <p style="margin: 10px 0;">
                <strong>参加リンク</strong><br>
                <a href="{{ $settings['line_openchat_link'] ?? '#' }}" style="color: #fff; word-break: break-all;">{{ $settings['line_openchat_link'] ?? '（未設定）' }}</a>
            </p>
            <p style="margin: 10px 0;">
                <strong>参加コード：</strong>{{ $settings['participation_code'] ?? '（未設定）' }}
            </p>
        </div>

        <div style="background: #f5f2ed; padding: 15px; border-radius: 4px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0; font-weight: bold;">▼ オープンチャットに参加すると...</p>
            <ul style="margin: 0; padding-left: 20px;">
                <li>今の自分を8つの視点で見える化する「ライフバランス診断」が受けられます</li>
                <li>セミナーのアーカイブ動画が見られます</li>
                <li>主催者のSayakaに質問したり、他の参加者と交流ができます</li>
            </ul>
        </div>

        <p style="margin-top: 20px;">まずはオープンチャットへ！<br>お会いできるのを楽しみにしています。</p>

        <div style="font-size: 0.85em; color: #666; margin-top: 30px; border-top: 1px solid #e0d8cc; padding-top: 20px;">
            <p style="margin: 5px 0;">※ セミナーはZoomで開催します</p>
            <p style="margin: 5px 0;">※ 品質改善のため録画させていただく場合があります（公開はしません）</p>
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
