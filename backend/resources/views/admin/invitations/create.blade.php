@extends('layouts.admin')

@section('title', '招待を作成')

@section('content')
<div class="admin-header">
    <h2>招待を作成</h2>
</div>

<div class="admin-card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.invitations.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">権限 <span style="color: var(--color-orange);">*</span></label>
            <select name="role" class="form-select" required>
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <p style="margin-top: 8px; font-size: 0.85rem; color: var(--foreground-muted);">
                <strong>管理者:</strong> すべての機能にアクセス可能（招待作成含む）<br>
                <strong>編集者:</strong> コンテンツの閲覧・編集のみ（招待作成不可）
            </p>
            @error('role')
                <p class="form-error" style="color: var(--color-orange);">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">メールアドレス（任意）</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="特定のメールアドレスに限定する場合">
            <p style="margin-top: 8px; font-size: 0.85rem; color: var(--foreground-muted);">
                指定すると、そのメールアドレスでのみ登録可能になります。
            </p>
            @error('email')
                <p class="form-error" style="color: var(--color-orange);">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">有効期間（日数） <span style="color: var(--color-orange);">*</span></label>
            <select name="expires_in_days" class="form-select" required>
                <option value="1" {{ old('expires_in_days') == 1 ? 'selected' : '' }}>1日</option>
                <option value="3" {{ old('expires_in_days') == 3 ? 'selected' : '' }}>3日</option>
                <option value="7" {{ old('expires_in_days', 7) == 7 ? 'selected' : '' }}>7日（デフォルト）</option>
                <option value="14" {{ old('expires_in_days') == 14 ? 'selected' : '' }}>14日</option>
                <option value="30" {{ old('expires_in_days') == 30 ? 'selected' : '' }}>30日</option>
            </select>
            @error('expires_in_days')
                <p class="form-error" style="color: var(--color-orange);">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">招待リンクを作成</button>
            <a href="{{ route('admin.invitations.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
