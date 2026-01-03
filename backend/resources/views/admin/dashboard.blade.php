@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
<div class="admin-header">
    <h2>ダッシュボード</h2>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <h3>総登録者数</h3>
        <div class="value">{{ number_format($stats['subscribers']) }}</div>
    </div>
    <div class="stat-card">
        <h3>アクティブ登録者</h3>
        <div class="value">{{ number_format($stats['active_subscribers']) }}</div>
    </div>
    <div class="stat-card">
        <h3>診断結果数</h3>
        <div class="value">{{ number_format($stats['diagnosis_results']) }}</div>
    </div>
    <div class="stat-card">
        <h3>セミナー申込数</h3>
        <div class="value" style="color: #ff6b35;">{{ number_format($stats['seminar_applications']) }}</div>
    </div>
    <div class="stat-card">
        <h3>配信停止数</h3>
        <div class="value" style="color: #9a9a9a;">{{ number_format($stats['unsubscribed']) }}</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <div class="admin-card">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">最近のセミナー申込</h3>
        @if($recentApplications->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>日時</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentApplications as $app)
                    <tr>
                        <td>{{ $app->name }}</td>
                        <td>{{ $app->created_at->format('m/d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: var(--admin-text-light);">まだ申込はありません</p>
        @endif
    </div>

    <div class="admin-card">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">最近のメルマガ登録</h3>
        @if($recentSubscribers->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>メール</th>
                        <th>日時</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSubscribers as $sub)
                    <tr>
                        <td>{{ $sub->email }}</td>
                        <td>{{ $sub->created_at->format('m/d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: var(--admin-text-light);">まだ登録はありません</p>
        @endif
    </div>
</div>
@endsection
