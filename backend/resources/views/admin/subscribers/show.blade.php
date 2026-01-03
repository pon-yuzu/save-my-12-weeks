@extends('layouts.admin')

@section('title', '登録者詳細')

@section('content')
<div class="admin-header">
    <h2>登録者詳細</h2>
    <a href="{{ route('admin.subscribers.index') }}" class="btn btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <table class="admin-table" style="max-width: 600px;">
        <tr>
            <th style="width: 150px;">ID</th>
            <td>{{ $subscriber->id }}</td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td>{{ $subscriber->email }}</td>
        </tr>
        <tr>
            <th>ステータス</th>
            <td>
                @if($subscriber->is_active)
                    <span class="badge badge-success">アクティブ</span>
                @else
                    <span class="badge badge-danger">停止</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>講座進捗</th>
            <td>Day {{ $subscriber->current_day }} / 30</td>
        </tr>
        <tr>
            <th>登録日</th>
            <td>{{ $subscriber->subscribed_at?->format('Y/m/d H:i') ?? '-' }}</td>
        </tr>
        @if($subscriber->unsubscribed_at)
        <tr>
            <th>停止日</th>
            <td>{{ $subscriber->unsubscribed_at->format('Y/m/d H:i') }}</td>
        </tr>
        @endif
    </table>
</div>

@if($subscriber->diagnosisResult)
<div class="admin-card">
    <h3 style="margin-bottom: 16px;">診断結果</h3>
    <a href="{{ route('admin.diagnosis.show', $subscriber->diagnosisResult) }}" class="btn btn-secondary btn-sm">診断結果を見る</a>
</div>
@endif

@if($subscriber->unsubscribeReason)
<div class="admin-card">
    <h3 style="margin-bottom: 16px;">配信停止理由</h3>
    <p>{{ $subscriber->unsubscribeReason->reason }}</p>
    <p style="color: var(--admin-text-light); margin-top: 8px; font-size: 0.85rem;">
        停止日時: {{ $subscriber->unsubscribeReason->unsubscribed_at->format('Y/m/d H:i') }}
    </p>
</div>
@endif

@if($subscriber->deliveries->count() > 0)
<div class="admin-card">
    <h3 style="margin-bottom: 16px;">配信履歴</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>件名</th>
                <th>ステータス</th>
                <th>送信日時</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriber->deliveries as $delivery)
            <tr>
                <td>{{ $delivery->template->day_number }}</td>
                <td>{{ $delivery->template->subject }}</td>
                <td>
                    @if($delivery->status === 'sent')
                        <span class="badge badge-success">送信済</span>
                    @elseif($delivery->status === 'failed')
                        <span class="badge badge-danger">失敗</span>
                    @else
                        <span class="badge badge-warning">待機中</span>
                    @endif
                </td>
                <td>{{ $delivery->sent_at?->format('Y/m/d H:i') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
