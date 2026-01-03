@extends('layouts.admin')

@section('title', 'メールテンプレート')

@section('content')
<div class="admin-header">
    <h2>メールテンプレート（30日講座）</h2>
    <a href="{{ route('admin.mail-templates.create') }}" class="btn btn-primary">新規作成</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>件名</th>
                <th>ステータス</th>
                <th>更新日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($templates as $template)
            <tr>
                <td>Day {{ $template->day_number }}</td>
                <td>{{ Str::limit($template->subject, 50) }}</td>
                <td>
                    @if($template->is_active)
                        <span class="badge badge-success">有効</span>
                    @else
                        <span class="badge badge-danger">無効</span>
                    @endif
                </td>
                <td>{{ $template->updated_at->format('Y/m/d H:i') }}</td>
                <td style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.mail-templates.edit', $template) }}" class="btn btn-secondary btn-sm">編集</a>
                    <form action="{{ route('admin.mail-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: var(--admin-text-light);">テンプレートがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="admin-card">
    <h3 style="margin-bottom: 16px;">作成状況</h3>
    <p>{{ $templates->count() }} / 30 日分作成済み</p>
    <div style="background: #e2e8f0; height: 8px; border-radius: 4px; margin-top: 8px;">
        <div style="background: #0d7377; height: 100%; width: {{ ($templates->count() / 30) * 100 }}%; border-radius: 4px;"></div>
    </div>
</div>
@endsection
