<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnsubscribeReason;
use Illuminate\Http\Request;

class UnsubscribeReasonController extends Controller
{
    public function index(Request $request)
    {
        $query = UnsubscribeReason::with('subscription');

        if ($request->filled('search')) {
            $query->whereHas('subscription', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%');
            });
        }

        $reasons = $query->latest('unsubscribed_at')->paginate(20);

        return view('admin.unsubscribe-reasons.index', compact('reasons'));
    }
}
