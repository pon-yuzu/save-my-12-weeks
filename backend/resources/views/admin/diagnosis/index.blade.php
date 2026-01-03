@extends('layouts.admin')

@section('title', '診断結果一覧')

@section('content')
<div class="admin-header">
    <h2>診断結果一覧</h2>
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
                <th>メール</th>
                <th>平均スコア</th>
                <th>診断日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $result)
            <tr>
                <td>{{ $result->id }}</td>
                <td>{{ $result->subscription?->email ?? '(未登録)' }}</td>
                <td>{{ number_format($result->average_score, 1) }} / 10</td>
                <td>{{ $result->created_at->format('Y/m/d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.diagnosis.show', $result) }}" class="btn btn-secondary btn-sm">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: var(--admin-text-light);">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $results->withQueryString()->links() }}
</div>
@endsection
