<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisResult;
use App\Models\MailDelivery;
use App\Models\MailSubscription;
use App\Models\SeminarApplication;
use App\Models\UnsubscribeReason;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSent = MailDelivery::sent()->count();
        $totalOpened = MailDelivery::sent()->opened()->count();
        $openRate = $totalSent > 0 ? round(($totalOpened / $totalSent) * 100, 1) : 0;

        $stats = [
            'subscribers' => MailSubscription::count(),
            'active_subscribers' => MailSubscription::active()->count(),
            'diagnosis_results' => DiagnosisResult::count(),
            'seminar_applications' => SeminarApplication::count(),
            'unsubscribed' => UnsubscribeReason::count(),
            'emails_sent' => $totalSent,
            'emails_opened' => $totalOpened,
            'open_rate' => $openRate,
        ];

        $recentApplications = SeminarApplication::latest()->take(5)->get();
        $recentSubscribers = MailSubscription::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentApplications', 'recentSubscribers'));
    }
}
