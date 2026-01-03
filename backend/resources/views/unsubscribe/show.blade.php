@extends('layouts.base')

@section('title', '配信停止 | Save My 12 Weeks')

@section('content')
<div class="form-container" style="max-width: 580px;">
    <div style="margin-bottom: 32px;">
        <p style="font-size: 1.1rem; line-height: 2; color: var(--foreground);">
            ここまで読んでくれてありがとう。
        </p>

        <p style="margin-top: 24px; line-height: 2; color: var(--foreground-light);">
            やめる理由、なんとなくわかります。
        </p>

        <p style="margin-top: 16px; line-height: 2; color: var(--foreground-light);">
            無料だから。<br>
            続けられなくて嫌な気持ちになるから。<br>
            合わないと思ったから。<br>
            もっと合うものを見つけたから。
        </p>

        <p style="margin-top: 16px; line-height: 2; color: var(--foreground-light);">
            どれも、わかる。
        </p>

        <p style="margin-top: 24px; line-height: 2; color: var(--foreground);">
            でも一つだけ、知っておいてほしいことがあります。
        </p>

        <p style="margin-top: 16px; line-height: 2; color: var(--foreground-light);">
            無料のものは、どんなに質が高くても、私たちは軽く見てしまう。<br>
            だって財布が傷つかないから。
        </p>

        <p style="margin-top: 24px; line-height: 2; color: var(--foreground);">
            だから、もしあなたが<br>
            「タダのものはタダなりの価値しかない」<br>
            「無料の範囲では変われない」<br>
            って気づけたなら、どうか自分のために投資をしてください。
        </p>

        <p style="margin-top: 16px; line-height: 2; color: var(--foreground-light);">
            それが私じゃなくてもいいです。
        </p>

        <p style="margin-top: 24px; line-height: 2; color: var(--foreground-light);">
            私みたいに100万払った語学学校で<br>
            「いい経験になった」「まあ友達はできた」<br>
            なんて自己満足で終わらせないで。
        </p>

        <p style="margin-top: 16px; line-height: 2; color: var(--foreground);">
            成長や成果が出せると、あなたが信じられるものに。
        </p>
    </div>

    <div style="border-top: 1px solid rgba(0,0,0,0.1); padding-top: 32px;">
        @if ($errors->any())
            <div class="alert-error" style="margin-bottom: 16px;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('unsubscribe.process', $subscription->token) }}">
            @csrf

            <div class="form-group">
                <label class="form-label" style="font-size: 1rem; font-weight: 600; color: var(--foreground);">
                    この30日の代わりに、何に投資しますか？
                </label>
                <textarea name="reason" class="form-textarea styled-textarea" style="min-height: 120px;" placeholder="自由にお書きください" required>{{ old('reason') }}</textarea>
            </div>

            <button type="submit" class="form-submit" style="background: var(--foreground-light);">
                配信停止する
            </button>
        </form>
    </div>
</div>
@endsection
