@extends('layouts.base')

@section('title', 'セミナー申込 | Save My 12 Weeks')
@section('description', '「私の12週間を取り戻せ」セミナーへのお申込みフォーム')

@section('content')
<div class="form-container">
    <h1 class="form-heading">セミナー申込フォーム</h1>
    <p class="form-subheading">以下の項目をご記入ください</p>

    @if($seminar)
    <div class="seminar-info-card">
        <div class="seminar-date">
            <span class="seminar-label">開催日時</span>
            <span class="seminar-value">{{ $seminar->formatted_schedule }}</span>
        </div>
        <div class="seminar-method">
            <span class="seminar-label">開催方法</span>
            <span class="seminar-value">オンライン（Zoom）</span>
        </div>
        @if($seminar->capacity && !$seminar->is_full)
        <div class="seminar-capacity">
            <span class="seminar-label">残り</span>
            <span class="seminar-value">{{ $seminar->capacity - $seminar->applications_count }}名</span>
        </div>
        @endif
    </div>
    @endif

    @if ($errors->any())
    <div class="alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('seminar.submit') }}" id="seminarForm">
        @csrf

        <!-- お名前 -->
        <div class="form-group">
            <label class="form-label">
                お名前<span class="required">*</span>
            </label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- メールアドレス -->
        <div class="form-group">
            <label class="form-label">
                メールアドレス<span class="required">*</span>
            </label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 年代 -->
        <div class="form-group">
            <label class="form-label">
                年代<span class="required">*</span>
            </label>
            <div class="form-radio-group">
                @foreach($ageGroups as $value => $label)
                <label class="form-radio-label {{ old('age_group') == $value ? 'selected' : '' }}">
                    <input type="radio" name="age_group" value="{{ $value }}" {{ old('age_group') == $value ? 'checked' : '' }} required>
                    {{ $label }}
                </label>
                @endforeach
            </div>
            @error('age_group')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- ご職業 -->
        <div class="form-group">
            <label class="form-label">
                ご職業<span class="required">*</span>
            </label>
            <div class="form-radio-group">
                @foreach($occupations as $value => $label)
                <label class="form-radio-label {{ old('occupation') == $value ? 'selected' : '' }}">
                    <input type="radio" name="occupation" value="{{ $value }}" {{ old('occupation') == $value ? 'checked' : '' }} required>
                    {{ $label }}
                </label>
                @endforeach
            </div>
            <div class="conditional-field {{ old('occupation') == 'other' ? 'show' : '' }}" id="occupationOtherField">
                <input type="text" name="occupation_other" class="form-input" placeholder="具体的にご記入ください" value="{{ old('occupation_other') }}">
            </div>
            @error('occupation')
                <p class="form-error">{{ $message }}</p>
            @enderror
            @error('occupation_other')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 流入経路 -->
        <div class="form-group">
            <label class="form-label">
                このセミナーをどこで知りましたか？<span class="required">*</span>
            </label>
            <div class="form-radio-group">
                @foreach($referralSources as $value => $label)
                <label class="form-radio-label {{ old('referral_source') == $value ? 'selected' : '' }}">
                    <input type="radio" name="referral_source" value="{{ $value }}" {{ old('referral_source') == $value ? 'checked' : '' }} required>
                    {{ $label }}
                </label>
                @endforeach
            </div>
            <div class="conditional-field {{ old('referral_source') == 'other' ? 'show' : '' }}" id="referralOtherField">
                <input type="text" name="referral_other" class="form-input" placeholder="具体的にご記入ください" value="{{ old('referral_other') }}">
            </div>
            @error('referral_source')
                <p class="form-error">{{ $message }}</p>
            @enderror
            @error('referral_other')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 予定キャンセル経験 -->
        <div class="form-group">
            <label class="form-label">
                本当にやりたいことがあったのに、仕事が忙しいという理由で予定をキャンセルした経験はありますか？<span class="required">*</span>
            </label>
            <div class="form-radio-group">
                @foreach($hasCanceledOptions as $value => $label)
                <label class="form-radio-label {{ old('has_canceled_plans') == $value ? 'selected' : '' }}">
                    <input type="radio" name="has_canceled_plans" value="{{ $value }}" {{ old('has_canceled_plans') == $value ? 'checked' : '' }} required>
                    {{ $label }}
                </label>
                @endforeach
            </div>
            <div class="conditional-field {{ old('has_canceled_plans') == 'yes' ? 'show' : '' }}" id="cancelReasonField">
                <label class="form-label">キャンセル理由を教えてください</label>
                <textarea name="cancel_reason" class="form-textarea" placeholder="例：急な残業が入った、体調を崩した、など">{{ old('cancel_reason') }}</textarea>
            </div>
            @error('has_canceled_plans')
                <p class="form-error">{{ $message }}</p>
            @enderror
            @error('cancel_reason')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 12週間あったら何をしたいか -->
        <div class="form-group">
            <label class="form-label">
                もし12週間の自由な時間があったら、何をしたいですか？<span class="required">*</span>
            </label>
            <textarea name="twelve_weeks_dream" class="form-textarea" placeholder="自由にご記入ください" required>{{ old('twelve_weeks_dream') }}</textarea>
            @error('twelve_weeks_dream')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- その他ご質問やご要望 -->
        <div class="form-group">
            <label class="form-label">
                その他ご質問やご要望があればご記入ください
            </label>
            <textarea name="questions" class="form-textarea" placeholder="任意">{{ old('questions') }}</textarea>
            @error('questions')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="form-submit">申し込む</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Radio button selection styling
    document.querySelectorAll('.form-radio-label input').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const group = this.closest('.form-radio-group');
            group.querySelectorAll('.form-radio-label').forEach(function(label) {
                label.classList.remove('selected');
            });
            this.closest('.form-radio-label').classList.add('selected');
        });
    });

    // Conditional fields
    function toggleConditionalField(radioName, triggerValue, fieldId) {
        document.querySelectorAll('input[name="' + radioName + '"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const field = document.getElementById(fieldId);
                if (this.value === triggerValue) {
                    field.classList.add('show');
                } else {
                    field.classList.remove('show');
                }
            });
        });
    }

    toggleConditionalField('occupation', 'other', 'occupationOtherField');
    toggleConditionalField('referral_source', 'other', 'referralOtherField');
    toggleConditionalField('has_canceled_plans', 'yes', 'cancelReasonField');
});
</script>
@endpush
