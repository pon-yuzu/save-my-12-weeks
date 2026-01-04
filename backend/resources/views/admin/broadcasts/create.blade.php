@extends('layouts.admin')

@section('title', '新規メール作成')

@section('content')
<div class="admin-header">
    <h2>新規メール作成</h2>
    <a href="{{ route('admin.broadcasts.index') }}" class="btn btn-secondary">一覧に戻る</a>
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

    <form method="POST" action="{{ route('admin.broadcasts.store') }}" id="broadcastForm">
        @csrf

        <div class="form-group">
            <label class="form-label">件名</label>
            <input type="text" name="subject" class="form-input" value="{{ old('subject') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">本文</label>
            <textarea name="body" class="form-textarea" style="min-height: 250px;" required>{{ old('body') }}</textarea>
            <p style="margin-top: 8px; font-size: 0.85rem; color: var(--admin-text-light);">
                使える変数: ${name}（ニックネーム）, ${current_day}（現在Day）, ${areas_to_change}（選択した領域）
            </p>
        </div>

        <div class="form-group">
            <label class="form-label">送信先</label>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="target_type" value="all" {{ old('target_type', 'all') === 'all' ? 'checked' : '' }} onchange="toggleTargetOptions()">
                    <span>全員に送信</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="target_type" value="filtered" {{ old('target_type') === 'filtered' ? 'checked' : '' }} onchange="toggleTargetOptions()">
                    <span>条件でフィルター</span>
                </label>

                <div id="filterOptions" style="margin-left: 24px; display: none;">
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <label>Day
                            <input type="number" name="day_min" class="form-input" style="width: 80px;" placeholder="最小" value="{{ old('day_min') }}">
                        </label>
                        <span>〜</span>
                        <label>
                            <input type="number" name="day_max" class="form-input" style="width: 80px;" placeholder="最大" value="{{ old('day_max') }}">
                        </label>
                    </div>
                </div>

                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="target_type" value="individual" {{ old('target_type') === 'individual' ? 'checked' : '' }} onchange="toggleTargetOptions()">
                    <span>個別に選択</span>
                </label>

                <div id="individualOptions" style="margin-left: 24px; display: none;">
                    <select name="recipient_ids[]" class="form-select" multiple style="height: 200px;">
                        @foreach($subscribers as $subscriber)
                            <option value="{{ $subscriber->id }}" {{ in_array($subscriber->id, old('recipient_ids', [])) ? 'selected' : '' }}>
                                {{ $subscriber->nickname ?? '名前なし' }} ({{ $subscriber->email }}) - Day {{ $subscriber->current_day }}
                            </option>
                        @endforeach
                    </select>
                    <p style="margin-top: 4px; font-size: 0.85rem; color: var(--admin-text-light);">
                        Ctrl+クリックで複数選択
                    </p>
                </div>
            </div>
        </div>

        <div id="targetCount" style="padding: 12px; background: #f0f9ff; border-radius: 6px; margin-bottom: 20px;">
            対象者: <strong>読み込み中...</strong>
        </div>

        <div class="form-group">
            <label class="form-label">配信タイミング</label>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="send_timing" value="now" checked onchange="toggleScheduleOptions()">
                    <span>今すぐ送信</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="send_timing" value="scheduled" onchange="toggleScheduleOptions()">
                    <span>予約配信</span>
                </label>

                <div id="scheduleOptions" style="margin-left: 24px; display: none;">
                    <input type="datetime-local" name="scheduled_at" class="form-input" value="{{ old('scheduled_at') }}">
                </div>

                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="send_timing" value="draft" onchange="toggleScheduleOptions()">
                    <span>下書き保存</span>
                </label>
            </div>
        </div>

        <input type="hidden" name="send_now" id="sendNowInput" value="0">

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary" onclick="setSendNow()">保存・送信</button>
        </div>
    </form>
</div>

<script>
function toggleTargetOptions() {
    const targetType = document.querySelector('input[name="target_type"]:checked').value;
    document.getElementById('filterOptions').style.display = targetType === 'filtered' ? 'block' : 'none';
    document.getElementById('individualOptions').style.display = targetType === 'individual' ? 'block' : 'none';
    updateTargetCount();
}

function toggleScheduleOptions() {
    const timing = document.querySelector('input[name="send_timing"]:checked').value;
    document.getElementById('scheduleOptions').style.display = timing === 'scheduled' ? 'block' : 'none';
}

function setSendNow() {
    const timing = document.querySelector('input[name="send_timing"]:checked').value;
    document.getElementById('sendNowInput').value = timing === 'now' ? '1' : '0';
}

function updateTargetCount() {
    const targetType = document.querySelector('input[name="target_type"]:checked').value;
    const params = new URLSearchParams({ target_type: targetType });

    if (targetType === 'filtered') {
        const dayMin = document.querySelector('input[name="day_min"]').value;
        const dayMax = document.querySelector('input[name="day_max"]').value;
        if (dayMin) params.append('day_min', dayMin);
        if (dayMax) params.append('day_max', dayMax);
    } else if (targetType === 'individual') {
        const select = document.querySelector('select[name="recipient_ids[]"]');
        Array.from(select.selectedOptions).forEach(opt => params.append('recipient_ids[]', opt.value));
    }

    fetch(`{{ route('admin.broadcasts.preview') }}?${params}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('targetCount').innerHTML = `対象者: <strong>${data.count}人</strong>`;
        });
}

// 初期化
document.addEventListener('DOMContentLoaded', function() {
    toggleTargetOptions();
    toggleScheduleOptions();

    // フィルター変更時に対象者数を更新
    document.querySelectorAll('input[name="day_min"], input[name="day_max"]').forEach(el => {
        el.addEventListener('change', updateTargetCount);
    });
    document.querySelector('select[name="recipient_ids[]"]').addEventListener('change', updateTargetCount);
});
</script>
@endsection
