@extends('layouts.base')

@section('title', '配信時間の設定 | Save My 12 Weeks')

@section('content')
<div class="form-container" style="max-width: 480px;">
    <div style="margin-bottom: 32px; text-align: center;">
        <p style="font-size: 0.85rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--color-teal); margin-bottom: 12px;">
            Newsletter Settings
        </p>
        <h1 class="form-heading" style="font-size: 1.5rem;">配信時間の設定</h1>
        <p style="color: var(--foreground-light); margin-top: 12px; line-height: 1.8;">
            メールが届く時間を選んでください。<br>
            いつでも変更できます。
        </p>
    </div>

    @if ($errors->any())
        <div class="alert-error" style="margin-bottom: 24px;">
            @foreach ($errors->all() as $error)
                <p style="margin: 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('settings.time.update', $subscription->settings_token) }}">
        @csrf

        <div class="form-group">
            <div style="display: grid; gap: 12px;">
                @foreach ($preferredTimes as $time => $label)
                    <label class="time-option {{ $subscription->preferred_time === $time ? 'selected' : '' }}">
                        <input type="radio" name="preferred_time" value="{{ $time }}" {{ $subscription->preferred_time === $time ? 'checked' : '' }}>
                        <span class="time-option-content">
                            <span class="time-value">{{ $time }}</span>
                            <span class="time-label">{{ $label }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="form-submit" style="background: var(--color-teal);">
            この時間に設定する
        </button>
    </form>

    <div style="margin-top: 24px; text-align: center;">
        <p style="font-size: 0.85rem; color: var(--foreground-light);">
            現在の設定：{{ $preferredTimes[$subscription->preferred_time] ?? $subscription->preferred_time }}
        </p>
    </div>
</div>

<style>
    .time-option {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .time-option:hover {
        border-color: var(--color-teal);
        background: rgba(255, 255, 255, 0.8);
    }

    .time-option.selected,
    .time-option:has(input:checked) {
        border-color: var(--color-teal);
        background: var(--color-teal);
    }

    .time-option.selected .time-value,
    .time-option:has(input:checked) .time-value {
        color: #fff;
    }

    .time-option.selected .time-label,
    .time-option:has(input:checked) .time-label {
        color: rgba(255, 255, 255, 0.8);
    }

    .time-option input {
        display: none;
    }

    .time-option-content {
        display: flex;
        align-items: center;
        gap: 16px;
        width: 100%;
    }

    .time-value {
        font-family: var(--font-en);
        font-size: 1.25rem;
        font-weight: 500;
        color: var(--color-teal);
        min-width: 60px;
    }

    .time-label {
        font-size: 0.9rem;
        color: var(--foreground-light);
    }
</style>

@push('scripts')
<script>
    // ラジオボタン選択時にselectedクラスを更新
    document.querySelectorAll('.time-option input').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.time-option').forEach(option => {
                option.classList.remove('selected');
            });
            this.closest('.time-option').classList.add('selected');
        });
    });
</script>
@endpush
@endsection
