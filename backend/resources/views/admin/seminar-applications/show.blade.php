@extends('layouts.admin')

@section('title', 'セミナー申込詳細')

@section('content')
<div class="admin-header">
    <h2>セミナー申込詳細</h2>
    <a href="{{ route('admin.seminar-applications.index') }}" class="btn btn-secondary">一覧に戻る</a>
</div>

<div class="admin-card">
    <table class="admin-table" style="max-width: 700px;">
        <tr>
            <th style="width: 180px;">ID</th>
            <td>{{ $seminarApplication->id }}</td>
        </tr>
        <tr>
            <th>お名前</th>
            <td>{{ $seminarApplication->name }}</td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td><a href="mailto:{{ $seminarApplication->email }}">{{ $seminarApplication->email }}</a></td>
        </tr>
        <tr>
            <th>年代</th>
            <td>{{ $seminarApplication->age_group_label }}</td>
        </tr>
        <tr>
            <th>ご職業</th>
            <td>{{ $seminarApplication->occupation_label }}</td>
        </tr>
        <tr>
            <th>流入経路</th>
            <td>{{ $seminarApplication->referral_source_label }}</td>
        </tr>
        <tr>
            <th>予定キャンセル経験</th>
            <td>{{ $seminarApplication->has_canceled_plans_label }}</td>
        </tr>
        @if($seminarApplication->cancel_reason)
        <tr>
            <th>キャンセル理由</th>
            <td style="white-space: pre-wrap;">{{ $seminarApplication->cancel_reason }}</td>
        </tr>
        @endif
        <tr>
            <th>12週間あったら</th>
            <td style="white-space: pre-wrap;">{{ $seminarApplication->twelve_weeks_dream }}</td>
        </tr>
        @if($seminarApplication->questions)
        <tr>
            <th>その他質問</th>
            <td style="white-space: pre-wrap;">{{ $seminarApplication->questions }}</td>
        </tr>
        @endif
        <tr>
            <th>申込日時</th>
            <td>{{ $seminarApplication->created_at->format('Y年m月d日 H:i') }}</td>
        </tr>
    </table>
</div>

<div class="admin-card">
    <form action="{{ route('admin.seminar-applications.destroy', $seminarApplication) }}" method="POST" onsubmit="return confirm('本当に削除してもよろしいですか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">この申込を削除</button>
    </form>
</div>
@endsection
