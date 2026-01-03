<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisResult;
use App\Models\MailSubscription;
use App\Models\SeminarApplication;
use App\Models\UnsubscribeReason;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'subscribers' => MailSubscription::count(),
            'active_subscribers' => MailSubscription::active()->count(),
            'diagnosis_results' => DiagnosisResult::count(),
            'seminar_applications' => SeminarApplication::count(),
            'unsubscribed' => UnsubscribeReason::count(),
        ];

        $recentApplications = SeminarApplication::latest()->take(5)->get();
        $recentSubscribers = MailSubscription::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentApplications', 'recentSubscribers'));
    }
}
