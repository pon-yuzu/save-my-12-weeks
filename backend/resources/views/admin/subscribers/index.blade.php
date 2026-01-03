@extends('layouts.admin')

@section('title', '登録者管理')

@section('content')
<div class="admin-header">
    <h2>登録者管理</h2>
</div>

<div class="admin-card">
    <form class="search-form" method="GET">
        <input type="text" name="search" class="form-input" placeholder="メールアドレスで検索" value="{{ request('search') }}">
        <select name="status" class="form-select" style="width: auto;">
            <option value="">すべて</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>アクティブ</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>停止中</option>
        </select>
        <button type="submit" class="btn btn-secondary">検索</button>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>メールアドレス</th>
                <th>ステータス</th>
                <th>講座進捗</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subscribers as $subscriber)
            <tr>
                <td>{{ $subscriber->id }}</td>
                <td>{{ $subscriber->email }}</td>
                <td>
                    @if($subscriber->is_active)
                        <span class="badge badge-success">アクティブ</span>
                    @else
                        <span class="badge badge-danger">停止</span>
                    @endif
                </td>
                <td>Day {{ $subscriber->current_day }} / 30</td>
                <td>{{ $subscriber->created_at->format('Y/m/d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.subscribers.show', $subscriber) }}" class="btn btn-secondary btn-sm">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: var(--admin-text-light);">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $subscribers->withQueryString()->links() }}
</div>
@endsection
