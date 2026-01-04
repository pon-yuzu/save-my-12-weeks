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
            <div style="margin-top: 12px; padding: 16px; background: #f5f5f5; border-radius: 4px; font-size: 0.85rem;">
                <p style="margin: 0 0 8px; font-weight: 500; color: var(--admin-text);">利用可能な変数（件名・本文で使用可）：</p>
                <ul style="margin: 0; padding-left: 20px; color: var(--admin-text-light); line-height: 1.8;">
                    <li><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">${areas_to_change}</code> - 診断で選んだ「変えたい項目」</li>
                    <li><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">${day_number}</code> - 現在のDay番号</li>
                    <li><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">${unsubscribe_url}</code> - 配信停止URL</li>
                    <li><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">${seminar_application_url}</code> - セミナー申込URL</li>
                    <li><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">${next_seminar_date}</code> - 次回セミナー日程</li>
                    <li><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">${zoom_url}</code> - Zoom URL</li>
                    <li><code style="background: #fff; padding: 2px 6px; border-radius: 3px;">${line_openchat_url}</code> - LINEオープンチャットURL</li>
                </ul>
                <p style="margin: 8px 0 0; color: var(--admin-text-light);">HTMLタグも使用可能です。</p>
            </div>
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
