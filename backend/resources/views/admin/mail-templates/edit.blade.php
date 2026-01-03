@extends('layouts.admin')

@section('title', 'メールテンプレート編集')

@section('content')
<div class="admin-header">
    <h2>メールテンプレート編集（Day {{ $mailTemplate->day_number }}）</h2>
    <a href="{{ route('admin.mail-templates.index') }}" class="btn btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.mail-templates.update', $mailTemplate) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Day番号</label>
            <input type="text" class="form-input" value="Day {{ $mailTemplate->day_number }}" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">件名</label>
            <input type="text" name="subject" class="form-input" value="{{ old('subject', $mailTemplate->subject) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">本文（HTML）</label>
            <textarea name="body" class="form-textarea" style="min-height: 300px;" required>{{ old('body', $mailTemplate->body) }}</textarea>
            <p style="margin-top: 8px; font-size: 0.85rem; color: var(--admin-text-light);">
                HTMLタグが使用できます。変数: {name}（名前）, {unsubscribe_url}（配信停止URL）
            </p>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $mailTemplate->is_active) ? 'checked' : '' }}>
                <span>有効にする</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
