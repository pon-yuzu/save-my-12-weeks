<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeminarApplicationRequest;
use App\Mail\SeminarApplicationConfirmation;
use App\Mail\SeminarApplicationNotification;
use App\Models\Seminar;
use App\Models\SeminarApplication;
use App\Models\SeminarSetting;
use Illuminate\Support\Facades\Mail;

class SeminarController extends Controller
{
    public function showForm()
    {
        $upcomingSeminar = Seminar::upcoming();

        return view('seminar.form', [
            'ageGroups' => SeminarApplication::AGE_GROUPS,
            'occupations' => SeminarApplication::OCCUPATIONS,
            'referralSources' => SeminarApplication::REFERRAL_SOURCES,
            'hasCanceledOptions' => SeminarApplication::HAS_CANCELED_OPTIONS,
            'seminar' => $upcomingSeminar,
        ]);
    }

    public function submit(SeminarApplicationRequest $request)
    {
        $upcomingSeminar = Seminar::upcoming();

        $applicationData = $request->validated();

        // 直近のセミナーがあれば紐付け
        if ($upcomingSeminar) {
            $applicationData['seminar_id'] = $upcomingSeminar->id;
        }

        $application = SeminarApplication::create($applicationData);

        // セミナーの情報をメール用に取得
        $settings = $this->getSeminarSettings($upcomingSeminar);

        // 申込者への確認メール
        Mail::to($application->email)->queue(
            new SeminarApplicationConfirmation($application, $settings)
        );

        // 管理者への通知メール
        Mail::to(config('mail.admin_notification_to', 'ponglish.yukarizu@gmail.com'))->queue(
            new SeminarApplicationNotification($application)
        );

        return redirect()->route('seminar.complete')
            ->with('settings', $settings)
            ->with('seminar', $upcomingSeminar);
    }

    public function complete()
    {
        $upcomingSeminar = Seminar::upcoming();
        $settings = session('settings', $this->getSeminarSettings($upcomingSeminar));

        return view('seminar.complete', [
            'settings' => $settings,
            'seminar' => session('seminar', $upcomingSeminar),
        ]);
    }

    /**
     * セミナーから設定情報を取得（後方互換性のため）
     */
    private function getSeminarSettings(?Seminar $seminar): array
    {
        // セミナーが設定されていればそれを使用
        if ($seminar) {
            return [
                'schedule' => $seminar->formatted_schedule,
                'zoom_link' => $seminar->zoom_link,
                'line_openchat_link' => $seminar->line_openchat_link,
                'participation_code' => $seminar->participation_code,
            ];
        }

        // フォールバック: 旧SeminarSettingを使用
        return SeminarSetting::getAll();
    }
}
