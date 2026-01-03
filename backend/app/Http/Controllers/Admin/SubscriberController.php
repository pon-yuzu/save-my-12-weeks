<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailSubscription;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = MailSubscription::with('diagnosisResult');

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->latest()->paginate(20);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function show(MailSubscription $subscriber)
    {
        $subscriber->load(['diagnosisResult', 'deliveries.template', 'unsubscribeReason']);

        return view('admin.subscribers.show', compact('subscriber'));
    }
}
