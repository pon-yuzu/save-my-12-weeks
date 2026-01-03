@extends('layouts.admin')

@section('title', 'メールテンプレート作成')

@section('content')
<div class="admin-header">
    <h2>メールテンプレート作成</h2>
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

    <form method="POST" action="{{ route('admin.mail-templates.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Day番号</label>
            <select name="day_number" class="form-select" required>
                <option value="">選択してください</option>
                @foreach($availableDays as $day)
                    <option value="{{ $day }}" {{ old('day_number') == $day ? 'selected' : '' }}>Day {{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">件名</label>
            <input type="text" name="subject" class="form-input" value="{{ old('subject') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">本文（HTML）</label>
            <textarea name="body" class="form-textarea" style="min-height: 300px;" required>{{ old('body') }}</textarea>
            <p style="margin-top: 8px; font-size: 0.85rem; color: var(--admin-text-light);">
                HTMLタグが使用できます。変数: {name}（名前）, {unsubscribe_url}（配信停止URL）
            </p>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span>有効にする</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary">作成</button>
    </form>
</div>
@endsection
