@extends('layouts.admin')

@section('title', '配信停止理由一覧')

@section('content')
<div class="admin-header">
    <h2>配信停止理由一覧</h2>
</div>

<div class="admin-card">
    <form class="search-form" method="GET">
        <input type="text" name="search" class="form-input" placeholder="メールアドレスで検索" value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary">検索</button>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>メールアドレス</th>
                <th>理由</th>
                <th>停止日時</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reasons as $reason)
            <tr>
                <td>{{ $reason->id }}</td>
                <td>{{ $reason->subscription?->email ?? '(削除済み)' }}</td>
                <td style="max-width: 400px; white-space: pre-wrap;">{{ Str::limit($reason->reason, 100) }}</td>
                <td>{{ $reason->unsubscribed_at->format('Y/m/d H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: var(--admin-text-light);">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $reasons->withQueryString()->links() }}
</div>
@endsection
