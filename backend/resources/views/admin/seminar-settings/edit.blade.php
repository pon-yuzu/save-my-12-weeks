@extends('layouts.admin')

@section('title', 'セミナー設定')

@section('content')
<div class="admin-header">
    <h2>セミナー設定</h2>
</div>

<div class="admin-card">
    <p style="margin-bottom: 20px; color: var(--admin-text-light);">
        ここで設定した内容は、申込完了ページと確認メールに表示されます。
    </p>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.seminar-settings.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">日程（表示用文字列）</label>
            <input type="text" name="schedule" class="form-input" value="{{ old('schedule', $settings['schedule'] ?? '') }}" placeholder="例: 2026年2月1日（土）14:00〜16:00">
        </div>

        <div class="form-group">
            <label class="form-label">Zoom参加リンク</label>
            <input type="url" name="zoom_link" class="form-input" value="{{ old('zoom_link', $settings['zoom_link'] ?? '') }}" placeholder="https://zoom.us/j/...">
        </div>

        <div class="form-group">
            <label class="form-label">LINEオープンチャットリンク</label>
            <input type="url" name="line_openchat_link" class="form-input" value="{{ old('line_openchat_link', $settings['line_openchat_link'] ?? '') }}" placeholder="https://line.me/ti/g2/...">
        </div>

        <div class="form-group">
            <label class="form-label">参加コード</label>
            <input type="text" name="participation_code" class="form-input" value="{{ old('participation_code', $settings['participation_code'] ?? '') }}" placeholder="例: 12weeks">
        </div>

        <div class="form-group">
            <label class="form-label">案内テキスト（任意）</label>
            <textarea name="guidance_text" class="form-textarea" placeholder="追加の案内があれば記入">{{ old('guidance_text', $settings['guidance_text'] ?? '') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">保存</button>
    </form>
</div>
@endsection
