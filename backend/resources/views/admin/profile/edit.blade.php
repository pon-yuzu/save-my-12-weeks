@extends('layouts.admin')

@section('title', 'プロフィール編集')

@section('content')
<div class="admin-header">
    <h2>プロフィール編集</h2>
</div>

<div class="admin-card" style="max-width: 600px;">
    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">名前</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $admin->name) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $admin->email) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">権限</label>
            <input type="text" class="form-input" value="{{ $admin->role_label }}" disabled style="background: rgba(0,0,0,0.03);">
            <p style="margin-top: 4px; font-size: 0.85rem; color: var(--foreground-muted);">
                権限の変更は管理者にお問い合わせください
            </p>
        </div>

        <hr style="border: none; border-top: 1px solid var(--admin-border); margin: 28px 0;">

        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 20px; color: var(--foreground);">パスワード変更</h3>
        <p style="font-size: 0.9rem; color: var(--foreground-muted); margin-bottom: 20px;">
            パスワードを変更する場合のみ入力してください
        </p>

        <div class="form-group">
            <label class="form-label">現在のパスワード</label>
            <input type="password" name="current_password" class="form-input" autocomplete="current-password">
        </div>

        <div class="form-group">
            <label class="form-label">新しいパスワード</label>
            <input type="password" name="password" class="form-input" autocomplete="new-password">
            <p style="margin-top: 4px; font-size: 0.85rem; color: var(--foreground-muted);">
                8文字以上
            </p>
        </div>

        <div class="form-group">
            <label class="form-label">新しいパスワード（確認）</label>
            <input type="password" name="password_confirmation" class="form-input" autocomplete="new-password">
        </div>

        <div style="margin-top: 32px;">
            <button type="submit" class="btn btn-primary">保存する</button>
        </div>
    </form>
</div>
@endsection
