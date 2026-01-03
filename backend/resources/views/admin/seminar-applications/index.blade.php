@extends('layouts.admin')

@section('title', 'セミナー申込一覧')

@section('content')
<div class="admin-header">
    <h2>セミナー申込一覧</h2>
</div>

<div class="admin-card">
    <form class="search-form" method="GET">
        <input type="text" name="search" class="form-input" placeholder="名前・メールで検索" value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary">検索</button>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メール</th>
                <th>年代</th>
                <th>流入経路</th>
                <th>申込日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
            <tr>
                <td>{{ $app->id }}</td>
                <td>{{ $app->name }}</td>
                <td>{{ $app->email }}</td>
                <td>{{ $app->age_group_label }}</td>
                <td>{{ $app->referral_source_label }}</td>
                <td>{{ $app->created_at->format('Y/m/d H:i') }}</td>
                <td style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.seminar-applications.show', $app) }}" class="btn btn-secondary btn-sm">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: var(--admin-text-light);">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $applications->withQueryString()->links() }}
</div>
@endsection
