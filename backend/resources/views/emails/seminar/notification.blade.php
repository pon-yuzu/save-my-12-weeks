<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif; line-height: 1.8; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #faf8f5; padding: 30px; border-radius: 8px;">
        <h2 style="color: #0d7377; margin-bottom: 20px; border-bottom: 2px solid #0d7377; padding-bottom: 10px;">セミナー新規申込通知</h2>

        <p style="margin-bottom: 20px;">以下の内容で新規申込がありました。</p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed; width: 30%;">氏名</th>
                <td style="padding: 10px;">{{ $application->name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">メール</th>
                <td style="padding: 10px;"><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></td>
            </tr>
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">年代</th>
                <td style="padding: 10px;">{{ $application->age_group_label }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">職業</th>
                <td style="padding: 10px;">{{ $application->occupation_label }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">流入経路</th>
                <td style="padding: 10px;">{{ $application->referral_source_label }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">予定キャンセル経験</th>
                <td style="padding: 10px;">{{ $application->has_canceled_plans_label }}</td>
            </tr>
            @if($application->cancel_reason)
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">キャンセル理由</th>
                <td style="padding: 10px;">{{ $application->cancel_reason }}</td>
            </tr>
            @endif
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">12週間あったら</th>
                <td style="padding: 10px;">{{ $application->twelve_weeks_dream }}</td>
            </tr>
            @if($application->questions)
            <tr style="border-bottom: 1px solid #e0d8cc;">
                <th style="text-align: left; padding: 10px; background: #f5f2ed;">その他質問</th>
                <td style="padding: 10px;">{{ $application->questions }}</td>
            </tr>
            @endif
        </table>

        <div style="font-size: 0.85em; color: #666; margin-top: 30px; border-top: 1px solid #e0d8cc; padding-top: 20px;">
            <p style="margin: 5px 0;">申込日時：{{ $application->created_at->format('Y年m月d日 H:i') }}</p>
        </div>
    </div>
</body>
</html>
