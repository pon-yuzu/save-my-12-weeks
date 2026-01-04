@extends('layouts.admin')

@section('title', 'メール配信')

@section('content')
<div class="admin-header">
    <h2>メール配信</h2>
    <a href="{{ route('admin.broadcasts.create') }}" class="btn btn-primary">新規作成</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>件名</th>
                <th>種類</th>
                <th>ステータス</th>
                <th>送信数</th>
                <th>開封率</th>
                <th>作成日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($broadcasts as $broadcast)
            @php
                $openRate = $broadcast->sent_count > 0
                    ? round(($broadcast->opened_count / $broadcast->sent_count) * 100, 1)
                    : null;
            @endphp
            <tr>
                <td>{{ Str::limit($broadcast->subject, 40) }}</td>
                <td>
                    @switch($broadcast->target_type)
                        @case('all')
                            <span class="badge badge-info">全員</span>
                            @break
                        @case('individual')
                            <span class="badge badge-secondary">個別</span>
                            @break
                        @case('filtered')
                            <span class="badge badge-warning">フィルター</span>
                            @break
                    @endswitch
                </td>
                <td>
                    @switch($broadcast->status)
                        @case('draft')
                            <span class="badge badge-secondary">下書き</span>
                            @break
                        @case('scheduled')
                            <span class="badge badge-warning">予約中</span>
                            <br><small>{{ $broadcast->scheduled_at->format('m/d H:i') }}</small>
                            @break
                        @case('sending')
                            <span class="badge badge-info">送信中</span>
                            @break
                        @case('sent')
                            <span class="badge badge-success">送信済</span>
                            @break
                    @endswitch
                </td>
                <td>{{ number_format($broadcast->sent_count) }}</td>
                <td>
                    @if($openRate !== null)
                        <span style="color: {{ $openRate >= 30 ? '#22c55e' : ($openRate >= 20 ? '#f59e0b' : '#ef4444') }}; font-weight: 500;">
                            {{ $openRate }}%
                        </span>
                    @else
                        <span style="color: var(--admin-text-light);">-</span>
                    @endif
                </td>
                <td>{{ $broadcast->created_at->format('Y/m/d H:i') }}</td>
                <td style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.broadcasts.show', $broadcast) }}" class="btn btn-secondary btn-sm">詳細</a>
                    @if($broadcast->status === 'draft')
                        <a href="{{ route('admin.broadcasts.edit', $broadcast) }}" class="btn btn-primary btn-sm">編集</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: var(--admin-text-light);">配信履歴がありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $broadcasts->links() }}
</div>
@endsection
