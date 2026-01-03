@extends('layouts.base')

@section('title', 'お申込み完了 | Save My 12 Weeks')

@section('content')
<div class="form-container" style="text-align: center;">
    <div style="margin-bottom: 32px;">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#0d7377" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M9 12l2 2 4-4"></path>
        </svg>
    </div>

    <h1 class="form-heading" style="text-align: center;">お申込みありがとうございます</h1>

    <div style="text-align: left; margin-top: 32px; background: rgba(255,255,255,0.6); padding: 24px; border: 1px solid rgba(0,0,0,0.06);">
        <p style="margin-bottom: 16px;">セミナーへのお申込みを受け付けました。</p>

        <div style="background: #f5f2ed; padding: 16px; margin: 20px 0;">
            <p style="margin: 0;"><strong>日程：</strong>{{ $settings['schedule'] ?? '（未定）' }}</p>
            <p style="margin: 8px 0 0 0;"><strong>開催：</strong>オンライン（Zoom）</p>
        </div>

        <p style="color: var(--foreground-light);">詳細はご登録のメールアドレスにお送りしました。</p>
    </div>

    <div style="text-align: left; margin-top: 32px; background: #0d7377; color: #fff; padding: 24px;">
        <h2 style="margin: 0 0 16px 0; font-size: 1.1rem;">▼ 次のステップ</h2>
        <p style="margin-bottom: 16px;"><strong>オープンチャットに参加してください</strong></p>

        @if(!empty($settings['line_openchat_link']))
        <p style="margin-bottom: 8px;"><strong>参加リンク：</strong></p>
        <a href="{{ $settings['line_openchat_link'] }}" target="_blank" rel="noopener" style="color: #fff; word-break: break-all;">
            {{ $settings['line_openchat_link'] }}
        </a>
        @endif

        @if(!empty($settings['participation_code']))
        <p style="margin-top: 16px;"><strong>参加コード：</strong>{{ $settings['participation_code'] }}</p>
        @endif
    </div>

    <div style="text-align: left; margin-top: 24px; font-size: 0.9rem; color: var(--foreground-light);">
        <p style="margin: 4px 0;">※ セミナーはZoomで開催します</p>
        <p style="margin: 4px 0;">※ 品質改善のため録画させていただく場合があります（公開はしません）</p>
    </div>
</div>
@endsection
