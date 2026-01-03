@extends('layouts.admin')

@section('title', '招待管理')

@section('content')
<div class="admin-header">
    <h2>招待管理</h2>
    <a href="{{ route('admin.invitations.create') }}" class="btn btn-primary">新規招待を作成</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>権限</th>
                <th>メール</th>
                <th>招待者</th>
                <th>有効期限</th>
                <th>ステータス</th>
                <th>作成日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invitations as $invitation)
            <tr>
                <td>{{ $invitation->id }}</td>
                <td>
                    <span class="badge {{ $invitation->role === 'admin' ? 'badge-danger' : 'badge-success' }}">
                        {{ $invitation->role_label }}
                    </span>
                </td>
                <td>{{ $invitation->email ?? '（指定なし）' }}</td>
                <td>{{ $invitation->inviter->name ?? '-' }}</td>
                <td>{{ $invitation->expires_at->format('Y/m/d H:i') }}</td>
                <td>
                    <span class="badge {{ $invitation->status_badge }}">
                        {{ $invitation->status }}
                    </span>
                </td>
                <td>{{ $invitation->created_at->format('Y/m/d H:i') }}</td>
                <td style="display: flex; gap: 8px;">
                    @if($invitation->isValid())
                        <a href="{{ route('admin.invitations.show', $invitation) }}" class="btn btn-secondary btn-sm">リンク表示</a>
                    @endif
                    <form action="{{ route('admin.invitations.destroy', $invitation) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: var(--foreground-muted);">招待がありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $invitations->links() }}
</div>
@endsection
