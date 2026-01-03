<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeminarApplicationRequest;
use App\Mail\SeminarApplicationConfirmation;
use App\Mail\SeminarApplicationNotification;
use App\Models\SeminarApplication;
use App\Models\SeminarSetting;
use Illuminate\Support\Facades\Mail;

class SeminarController extends Controller
{
    public function showForm()
    {
        return view('seminar.form', [
            'ageGroups' => SeminarApplication::AGE_GROUPS,
            'occupations' => SeminarApplication::OCCUPATIONS,
            'referralSources' => SeminarApplication::REFERRAL_SOURCES,
            'hasCanceledOptions' => SeminarApplication::HAS_CANCELED_OPTIONS,
        ]);
    }

    public function submit(SeminarApplicationRequest $request)
    {
        $application = SeminarApplication::create($request->validated());

        $settings = SeminarSetting::getAll();

        // 申込者への確認メール
        Mail::to($application->email)->queue(
            new SeminarApplicationConfirmation($application, $settings)
        );

        // 管理者への通知メール
        Mail::to(config('mail.admin_notification_to', 'ponglish.yukarizu@gmail.com'))->queue(
            new SeminarApplicationNotification($application)
        );

        return redirect()->route('seminar.complete')
            ->with('settings', $settings);
    }

    public function complete()
    {
        $settings = session('settings', SeminarSetting::getAll());

        return view('seminar.complete', [
            'settings' => $settings,
        ]);
    }
}
