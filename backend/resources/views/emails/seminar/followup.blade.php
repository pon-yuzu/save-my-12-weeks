<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif; line-height: 1.8; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #faf8f5; padding: 30px; border-radius: 8px;">
        <p style="margin-bottom: 20px;">{{ $application->name }}さん</p>

        <p style="margin-bottom: 20px; font-size: 1.2em; font-weight: bold; color: #0d7377;">
            本日はセミナーにご参加いただき、ありがとうございました！
        </p>

        <p style="margin-bottom: 20px;">
            「私の12週間を取り戻せ」セミナー、いかがでしたか？
        </p>

        <p style="margin-bottom: 20px;">
            セミナーでお話しした内容が、少しでもあなたの背中を押すきっかけになれば嬉しいです。
        </p>

        <div style="background: #fff; border: 2px solid #ff6b35; padding: 20px; margin: 30px 0; border-radius: 8px; text-align: center;">
            <p style="margin: 0 0 15px 0; font-weight: bold; color: #ff6b35;">
                📝 1分で終わるアンケートにご協力ください
            </p>
            <p style="margin: 0 0 15px 0; font-size: 0.9em; color: #666;">
                みなさんの声が、次のセミナーをより良くします
            </p>
            <a href="{{ $feedback->url }}" style="display: inline-block; background: #ff6b35; color: #fff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                アンケートに回答する
            </a>
        </div>

        <div style="background: #f5f2ed; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 15px 0; font-weight: bold;">📚 次のステップ</p>
            <ul style="margin: 0; padding-left: 20px;">
                <li style="margin-bottom: 8px;">30日講座をまだ始めていない方は、ぜひスタートしてみてください</li>
                <li style="margin-bottom: 8px;">オープンチャットで質問があればいつでもどうぞ</li>
                <li style="margin-bottom: 8px;">個別セッションにも興味があれば、お気軽にご連絡ください</li>
            </ul>
        </div>

        <p style="margin-top: 20px;">
            また近いうちにお会いできることを楽しみにしています。<br>
            あなたの12週間が、素晴らしいものになりますように。
        </p>

        <p style="margin-top: 20px;">
            Sayaka
        </p>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0d8cc; font-size: 0.9em; color: #666;">
            <p style="margin: 5px 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
            <p style="margin: 5px 0;">Save My 12 Weeks｜私の12週間を取り戻せ</p>
            <p style="margin: 5px 0;">主催：Sayaka</p>
            <p style="margin: 5px 0; font-weight: bold;">━━━━━━━━━━━━━━━</p>
        </div>
    </div>
</body>
</html>
