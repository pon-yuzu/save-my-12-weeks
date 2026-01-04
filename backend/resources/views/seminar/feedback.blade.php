@extends('layouts.base')

@section('title', 'セミナーアンケート | Save My 12 Weeks')
@section('description', 'セミナーへのご参加ありがとうございました')

@section('content')
<div class="form-container">
    <h1 class="form-heading">セミナーアンケート</h1>
    <p class="form-subheading">{{ $feedback->application->name }}さん、ご参加ありがとうございました！<br>1分で終わる簡単なアンケートにご協力ください。</p>

    @if ($errors->any())
    <div class="alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('seminar.feedback.submit', ['token' => $feedback->token]) }}">
        @csrf

        <!-- 全体評価 -->
        <div class="form-group">
            <label class="form-label">
                セミナー全体の満足度<span class="required">*</span>
            </label>
            <div class="rating-group">
                @for($i = 1; $i <= 5; $i++)
                <label class="rating-label {{ old('overall_rating') == $i ? 'selected' : '' }}">
                    <input type="radio" name="overall_rating" value="{{ $i }}" {{ old('overall_rating') == $i ? 'checked' : '' }} required>
                    <span class="rating-star">{{ $i <= (old('overall_rating') ?? 0) ? '★' : '☆' }}</span>
                    <span class="rating-text">{{ ['', '不満', 'やや不満', '普通', '満足', 'とても満足'][$i] }}</span>
                </label>
                @endfor
            </div>
            @error('overall_rating')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 内容評価 -->
        <div class="form-group">
            <label class="form-label">
                セミナー内容の分かりやすさ<span class="required">*</span>
            </label>
            <div class="rating-group">
                @for($i = 1; $i <= 5; $i++)
                <label class="rating-label {{ old('content_rating') == $i ? 'selected' : '' }}">
                    <input type="radio" name="content_rating" value="{{ $i }}" {{ old('content_rating') == $i ? 'checked' : '' }} required>
                    <span class="rating-star">{{ $i <= (old('content_rating') ?? 0) ? '★' : '☆' }}</span>
                    <span class="rating-text">{{ ['', '難しい', 'やや難しい', '普通', '分かりやすい', 'とても分かりやすい'][$i] }}</span>
                </label>
                @endfor
            </div>
            @error('content_rating')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 一番役に立ったこと -->
        <div class="form-group">
            <label class="form-label">
                一番印象に残ったこと・役に立ったこと
            </label>
            <textarea name="most_helpful" class="form-textarea" placeholder="自由にご記入ください">{{ old('most_helpful') }}</textarea>
        </div>

        <!-- 改善点 -->
        <div class="form-group">
            <label class="form-label">
                もっとこうしてほしかったこと
            </label>
            <textarea name="improvement_suggestions" class="form-textarea" placeholder="率直なご意見をお聞かせください">{{ old('improvement_suggestions') }}</textarea>
        </div>

        <!-- 興味 -->
        <div class="form-group">
            <label class="form-label">今後について教えてください</label>
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="interested_in_program" value="1" {{ old('interested_in_program') ? 'checked' : '' }}>
                    12週間プログラムに興味がある
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="interested_in_session" value="1" {{ old('interested_in_session') ? 'checked' : '' }}>
                    個別セッションに興味がある
                </label>
            </div>
        </div>

        <!-- 質問 -->
        <div class="form-group">
            <label class="form-label">
                質問やメッセージがあればどうぞ
            </label>
            <textarea name="questions" class="form-textarea" placeholder="Sayakaへのメッセージ、質問など">{{ old('questions') }}</textarea>
        </div>

        <button type="submit" class="form-submit">送信する</button>
    </form>
</div>

<style>
.rating-group {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.rating-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 16px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 60px;
    text-align: center;
}

.rating-label:hover {
    border-color: var(--color-orange);
}

.rating-label.selected,
.rating-label:has(input:checked) {
    border-color: var(--color-orange);
    background: rgba(255, 107, 53, 0.1);
}

.rating-label input {
    display: none;
}

.rating-star {
    font-size: 1.5rem;
    color: var(--color-orange);
}

.rating-text {
    font-size: 0.75rem;
    color: var(--foreground-light);
    margin-top: 4px;
}

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.checkbox-label input {
    width: 18px;
    height: 18px;
    accent-color: var(--color-orange);
}
</style>

@push('scripts')
<script>
document.querySelectorAll('.rating-label input').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const group = this.closest('.rating-group');
        group.querySelectorAll('.rating-label').forEach(function(label) {
            label.classList.remove('selected');
        });
        this.closest('.rating-label').classList.add('selected');

        // 星を更新
        const rating = parseInt(this.value);
        group.querySelectorAll('.rating-star').forEach(function(star, index) {
            star.textContent = (index + 1) <= rating ? '★' : '☆';
        });
    });
});
</script>
@endpush
@endsection
