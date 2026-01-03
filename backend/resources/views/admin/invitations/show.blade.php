@extends('layouts.admin')

@section('title', '招待リンク')

@section('content')
<div class="admin-header">
    <h2>招待リンク</h2>
</div>

<div class="admin-card" style="max-width: 700px;">
    @if($invitation->isValid())
        <div style="margin-bottom: 24px;">
            <p style="margin-bottom: 16px; color: var(--foreground-light);">
                以下の招待リンクを相手に共有してください。
            </p>

            <div style="background: rgba(0,0,0,0.03); padding: 16px; border: 1px solid var(--admin-border); margin-bottom: 16px;">
                <input
                    type="text"
                    id="inviteUrl"
                    value="{{ $invitation->invite_url }}"
                    class="form-input"
                    readonly
                    style="font-family: monospace; font-size: 0.9rem;"
                >
            </div>

            <button type="button" onclick="copyToClipboard()" class="btn btn-primary">
                リンクをコピー
            </button>
        </div>
    @else
        <div class="alert alert-error">
            この招待リンクは{{ $invitation->used_at ? '使用済み' : '期限切れ' }}です。
        </div>
    @endif

    <table style="width: 100%;">
        <tr>
            <th style="text-align: left; padding: 8px 0; color: var(--foreground-muted); width: 120px;">権限</th>
            <td style="padding: 8px 0;">
                <span class="badge {{ $invitation->role === 'admin' ? 'badge-danger' : 'badge-success' }}">
                    {{ $invitation->role_label }}
                </span>
            </td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px 0; color: var(--foreground-muted);">対象メール</th>
            <td style="padding: 8px 0;">{{ $invitation->email ?? '（指定なし・誰でも使用可）' }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px 0; color: var(--foreground-muted);">有効期限</th>
            <td style="padding: 8px 0;">{{ $invitation->expires_at->format('Y年m月d日 H:i') }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px 0; color: var(--foreground-muted);">ステータス</th>
            <td style="padding: 8px 0;">
                <span class="badge {{ $invitation->status_badge }}">{{ $invitation->status }}</span>
            </td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px 0; color: var(--foreground-muted);">招待者</th>
            <td style="padding: 8px 0;">{{ $invitation->inviter->name ?? '-' }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px 0; color: var(--foreground-muted);">作成日時</th>
            <td style="padding: 8px 0;">{{ $invitation->created_at->format('Y年m月d日 H:i') }}</td>
        </tr>
    </table>

    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--admin-border);">
        <a href="{{ route('admin.invitations.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('inviteUrl');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);
    alert('リンクをコピーしました');
}
</script>
@endsection
