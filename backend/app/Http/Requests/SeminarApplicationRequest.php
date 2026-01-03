<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeminarApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'age_group' => ['required', 'in:20s,30s,40s,50s_plus,prefer_not'],
            'occupation' => ['required', 'string', 'max:255'],
            'occupation_other' => ['nullable', 'string', 'max:255', 'required_if:occupation,other'],
            'referral_source' => ['required', 'string', 'max:255'],
            'referral_other' => ['nullable', 'string', 'max:255', 'required_if:referral_source,other'],
            'has_canceled_plans' => ['required', 'in:yes,no,dont_remember'],
            'cancel_reason' => ['nullable', 'string', 'max:2000', 'required_if:has_canceled_plans,yes'],
            'twelve_weeks_dream' => ['required', 'string', 'max:2000'],
            'questions' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'お名前',
            'email' => 'メールアドレス',
            'age_group' => '年代',
            'occupation' => 'ご職業',
            'occupation_other' => 'その他のご職業',
            'referral_source' => '流入経路',
            'referral_other' => 'その他の流入経路',
            'has_canceled_plans' => '予定キャンセル経験',
            'cancel_reason' => 'キャンセル理由',
            'twelve_weeks_dream' => '12週間あったら何をしたいか',
            'questions' => 'ご質問やご要望',
        ];
    }

    public function messages(): array
    {
        return [
            'occupation_other.required_if' => '「その他」を選択した場合、具体的なご職業をご記入ください。',
            'referral_other.required_if' => '「その他」を選択した場合、具体的な経路をご記入ください。',
            'cancel_reason.required_if' => '「ある」を選択した場合、キャンセル理由をご記入ください。',
        ];
    }
}
