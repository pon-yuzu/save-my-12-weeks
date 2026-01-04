@extends('layouts.base')

@section('title', '設定完了 | Save My 12 Weeks')

@section('content')
<div class="form-container" style="max-width: 480px;">
    <div style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 24px;">✨</div>

        <p style="font-size: 0.85rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--color-teal); margin-bottom: 12px;">
            Complete
        </p>

        <h1 class="form-heading" style="font-size: 1.5rem; margin-bottom: 24px;">
            設定が完了しました
        </h1>

        <p style="color: var(--foreground-light); line-height: 2; margin-bottom: 32px;">
            選択した時間にメールをお届けします。<br>
            明日からの配信を楽しみにしていてください。
        </p>

        <div style="background: rgba(255, 255, 255, 0.6); border-left: 4px solid var(--color-teal); padding: 20px; text-align: left; margin-bottom: 32px;">
            <p style="margin: 0; font-weight: 500; color: var(--foreground);">
                ヒント
            </p>
            <p style="margin: 8px 0 0 0; color: var(--foreground-light); font-size: 0.9rem; line-height: 1.8;">
                メールが届かない場合は、迷惑メールフォルダを確認してください。<br>
                また、受信設定で「savemy12weeks.com」からのメールを許可してください。
            </p>
        </div>

        <a href="/" style="display: inline-block; padding: 12px 32px; background: var(--color-teal); color: #fff; text-decoration: none; font-size: 0.9rem; transition: background 0.3s ease;">
            トップページへ戻る
        </a>
    </div>
</div>
@endsection
