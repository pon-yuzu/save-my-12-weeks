@extends('layouts.base')

@section('title', 'アンケート送信完了 | Save My 12 Weeks')

@section('content')
<div class="form-container" style="text-align: center;">
    <div style="font-size: 4rem; margin-bottom: 20px;">🙏</div>
    <h1 class="form-heading">ありがとうございました！</h1>
    <p class="form-subheading">アンケートへのご協力ありがとうございます。</p>

    <div style="background: rgba(13, 115, 119, 0.1); padding: 24px; border-radius: 8px; margin: 30px 0;">
        <p style="margin: 0; color: var(--color-teal);">
            いただいたご意見は、今後のセミナーの改善に活用させていただきます。
        </p>
    </div>

    <p style="color: var(--foreground-light); margin-bottom: 30px;">
        30日講座やオープンチャットで、引き続きお会いできることを楽しみにしています！
    </p>

    <a href="/" class="form-submit" style="display: inline-block; text-decoration: none; width: auto; padding: 14px 32px;">
        トップページへ
    </a>
</div>
@endsection
