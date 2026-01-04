@extends('layouts.admin')

@section('title', 'メール詳細')

@section('content')
<div class="admin-header">
    <h2>メール詳細</h2>
    <div style="display: flex; gap: 8px;">
        @if($broadcast->status === 'draft')
            <a href="{{ route('admin.broadcasts.edit', $broadcast) }}" class="btn btn-primary">編集</a>
        @endif
        <a href="{{ route('admin.broadcasts.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </div>
</div>

<div class="admin-card">
    <h3 style="margin-bottom: 16px;">基本情報</h3>

    <dl style="display: grid; grid-template-columns: 120px 1fr; gap: 12px;">
        <dt style="color: var(--admin-text-light);">ステータス</dt>
        <dd>
            @switch($broadcast->status)
                @case('draft')
                    <span class="badge badge-secondary">下書き</span>
                    @break
                @case('scheduled')
                    <span class="badge badge-warning">予約中</span>
                    （{{ $broadcast->scheduled_at->format('Y/m/d H:i') }}）
                    @break
                @case('sending')
                    <span class="badge badge-info">送信中</span>
                    @break
                @case('sent')
                    <span class="badge badge-success">送信済</span>
                    （{{ $broadcast->sent_at->format('Y/m/d H:i') }}）
                    @break
            @endswitch
        </dd>

        <dt style="color: var(--admin-text-light);">送信先</dt>
        <dd>
            @switch($broadcast->target_type)
                @case('all')
                    全員
                    @break
                @case('individual')
                    個別選択（{{ count($broadcast->recipient_ids ?? []) }}人）
                    @break
                @case('filtered')
                    フィルター
                    @if($broadcast->target_filter)
                        @if(isset($broadcast->target_filter['day_min']) || isset($broadcast->target_filter['day_max']))
                            （Day {{ $broadcast->target_filter['day_min'] ?? '?' }} 〜 {{ $broadcast->target_filter['day_max'] ?? '?' }}）
                        @endif
                    @endif
                    @break
            @endswitch
        </dd>

        <dt style="color: var(--admin-text-light);">件名</dt>
        <dd>{{ $broadcast->subject }}</dd>
    </dl>
</div>

@if($broadcast->status !== 'draft')
<div class="admin-card">
    <h3 style="margin-bottom: 16px;">配信統計</h3>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
        <div style="text-align: center; padding: 16px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: bold; color: #0d7377;">{{ $stats['total'] }}</div>
            <div style="color: var(--admin-text-light);">対象者</div>
        </div>
        <div style="text-align: center; padding: 16px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: bold; color: #22c55e;">{{ $stats['sent'] }}</div>
            <div style="color: var(--admin-text-light);">送信成功</div>
        </div>
        <div style="text-align: center; padding: 16px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: bold; color: #3b82f6;">{{ $stats['opened'] }}</div>
            <div style="color: var(--admin-text-light);">開封</div>
        </div>
        <div style="text-align: center; padding: 16px; background: #f8fafc; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: bold; color: #ef4444;">{{ $stats['failed'] }}</div>
            <div style="color: var(--admin-text-light);">失敗</div>
        </div>
    </div>

    @if($stats['sent'] > 0)
    <div style="margin-top: 16px;">
        <strong>開封率:</strong>
        {{ round(($stats['opened'] / $stats['sent']) * 100, 1) }}%
    </div>
    @endif
</div>
@endif

<div class="admin-card">
    <h3 style="margin-bottom: 16px;">本文プレビュー</h3>
    <div style="background: #faf8f5; padding: 20px; border-radius: 8px; white-space: pre-wrap;">{{ $broadcast->body }}</div>
</div>

@if($broadcast->recipients->count() > 0)
<div class="admin-card">
    <h3 style="margin-bottom: 16px;">送信先一覧</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メール</th>
                <th>ステータス</th>
                <th>開封</th>
            </tr>
        </thead>
        <tbody>
            @foreach($broadcast->recipients as $recipient)
            <tr>
                <td>{{ $recipient->subscription->nickname ?? '-' }}</td>
                <td>{{ $recipient->subscription->email }}</td>
                <td>
                    @switch($recipient->status)
                        @case('pending')
                            <span class="badge badge-secondary">待機中</span>
                            @break
                        @case('sent')
                            <span class="badge badge-success">送信済</span>
                            @break
                        @case('failed')
                            <span class="badge badge-danger">失敗</span>
                            @break
                    @endswitch
                </td>
                <td>
                    @if($recipient->opened_at)
                        {{ $recipient->opened_at->format('m/d H:i') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($broadcast->status === 'draft')
<div class="admin-card" style="background: #fef3c7;">
    <form action="{{ route('admin.broadcasts.destroy', $broadcast) }}" method="POST" style="display: inline;" onsubmit="return confirm('本当に削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">削除</button>
    </form>
</div>
@endif
@endsection
