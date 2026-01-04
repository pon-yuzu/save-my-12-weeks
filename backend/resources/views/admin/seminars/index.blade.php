@extends('layouts.admin')

@section('title', 'セミナー管理')

@push('styles')
<style>
#calendar {
    max-width: 100%;
}

.fc {
    font-family: inherit;
}

.fc-toolbar-title {
    font-size: 1.3rem !important;
}

.fc-button {
    background-color: var(--color-teal) !important;
    border-color: var(--color-teal) !important;
}

.fc-button:hover {
    background-color: #0a5a5d !important;
}

.fc-button-active {
    background-color: #0a5a5d !important;
}

.fc-event {
    cursor: pointer;
    padding: 2px 4px;
}

.fc-daygrid-day:hover {
    background-color: #f5f5f5;
    cursor: pointer;
}

/* モーダルスタイル */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.1rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
    padding: 0;
    line-height: 1;
}

.modal-close:hover {
    color: #333;
}

#seminar-form {
    padding: 20px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #e0e0e0;
}

.btn-danger {
    background-color: #ef4444 !important;
    color: white;
    margin-right: auto;
}

.btn-danger:hover {
    background-color: #dc2626 !important;
}
</style>
@endpush

@section('content')
<div class="admin-header">
    <h2>セミナー日程管理</h2>
</div>

<div class="admin-card">
    <p style="margin-bottom: 16px; color: var(--foreground-muted); font-size: 0.9rem;">
        カレンダーの日付をクリックしてセミナーを追加できます。イベントをドラッグで日程変更も可能です。
    </p>
    <div id="calendar" data-events-url="{{ route('admin.seminars.events') }}"></div>
</div>

<!-- セミナー作成/編集モーダル -->
<div id="seminar-modal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">セミナーを追加</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="seminar-form">
            <input type="hidden" id="seminar-id">

            <div class="form-group">
                <label class="form-label">タイトル</label>
                <input type="text" id="seminar-title" class="form-input" value="12週間プログラム説明会" required>
            </div>

            <div class="form-group">
                <label class="form-label">日時</label>
                <input type="datetime-local" id="seminar-datetime" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">所要時間（分）</label>
                <input type="number" id="seminar-duration" class="form-input" value="120" min="15" max="480">
            </div>

            <div class="form-group">
                <label class="form-label">Zoomリンク</label>
                <input type="url" id="seminar-zoom" class="form-input" placeholder="https://zoom.us/j/...">
            </div>

            <div class="form-group">
                <label class="form-label">LINEオープンチャットリンク</label>
                <input type="url" id="seminar-line" class="form-input" placeholder="https://line.me/ti/g2/...">
            </div>

            <div class="form-group">
                <label class="form-label">参加コード</label>
                <input type="text" id="seminar-code" class="form-input" placeholder="例: 12weeks">
            </div>

            <div class="form-group">
                <label class="form-label">定員（空欄で無制限）</label>
                <input type="number" id="seminar-capacity" class="form-input" min="1" placeholder="例: 20">
            </div>

            <div class="form-group">
                <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="seminar-active" checked style="width: auto;">
                    募集中
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">備考</label>
                <textarea id="seminar-description" class="form-textarea" rows="3"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" id="delete-btn" class="btn btn-danger" style="display: none;" onclick="deleteSeminar()">削除</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">キャンセル</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin-calendar.js'])
@endpush
