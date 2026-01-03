@extends('layouts.admin')

@section('title', '診断結果詳細')

@section('content')
<div class="admin-header">
    <h2>診断結果詳細</h2>
    <a href="{{ route('admin.diagnosis.index') }}" class="btn btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <h3 style="margin-bottom: 16px;">基本情報</h3>
    <table class="admin-table" style="max-width: 400px;">
        <tr>
            <th>メール</th>
            <td>{{ $diagnosis->subscription?->email ?? '(未登録)' }}</td>
        </tr>
        <tr>
            <th>診断日</th>
            <td>{{ $diagnosis->created_at->format('Y/m/d H:i') }}</td>
        </tr>
        <tr>
            <th>平均スコア</th>
            <td>{{ number_format($diagnosis->average_score, 1) }} / 10</td>
        </tr>
    </table>
</div>

<div class="admin-card">
    <h3 style="margin-bottom: 16px;">各カテゴリスコア</h3>
    @php
        $categories = [
            'health_score' => '健康',
            'mind_score' => '心の充実',
            'money_score' => 'お金',
            'career_score' => '仕事・キャリア',
            'time_score' => '時間',
            'living_score' => '暮らし',
            'relationships_score' => '人間関係',
            'vision_score' => '将来のビジョン',
        ];
    @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        @foreach($categories as $key => $label)
        <div style="background: #f8fafc; padding: 16px; border-radius: 6px;">
            <div style="font-size: 0.85rem; color: var(--admin-text-light); margin-bottom: 4px;">{{ $label }}</div>
            <div style="font-size: 1.5rem; font-weight: 600;">{{ $diagnosis->$key }} <span style="font-size: 0.9rem; font-weight: 400;">/ 10</span></div>
            <div style="background: #e2e8f0; height: 4px; border-radius: 2px; margin-top: 8px;">
                <div style="background: #0d7377; height: 100%; width: {{ $diagnosis->$key * 10 }}%; border-radius: 2px;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if($diagnosis->selected_areas && count($diagnosis->selected_areas) > 0)
<div class="admin-card">
    <h3 style="margin-bottom: 16px;">改善したい領域</h3>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        @foreach($diagnosis->selected_areas as $area)
            <span class="badge badge-success">{{ $categories[$area . '_score'] ?? $area }}</span>
        @endforeach
    </div>
</div>
@endif

@if($diagnosis->free_text)
<div class="admin-card">
    <h3 style="margin-bottom: 16px;">自由記述</h3>
    <p style="white-space: pre-wrap;">{{ $diagnosis->free_text }}</p>
</div>
@endif
@endsection
