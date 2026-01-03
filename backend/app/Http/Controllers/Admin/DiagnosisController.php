<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisResult;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $query = DiagnosisResult::with('subscription');

        if ($request->filled('search')) {
            $query->whereHas('subscription', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%');
            });
        }

        $results = $query->latest()->paginate(20);

        return view('admin.diagnosis.index', compact('results'));
    }

    public function show(DiagnosisResult $diagnosis)
    {
        $diagnosis->load('subscription');

        return view('admin.diagnosis.show', compact('diagnosis'));
    }
}
