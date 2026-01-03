<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeminarApplication;
use Illuminate\Http\Request;

class SeminarApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = SeminarApplication::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(20);

        return view('admin.seminar-applications.index', compact('applications'));
    }

    public function show(SeminarApplication $seminarApplication)
    {
        return view('admin.seminar-applications.show', compact('seminarApplication'));
    }

    public function destroy(SeminarApplication $seminarApplication)
    {
        $seminarApplication->delete();

        return redirect()->route('admin.seminar-applications.index')
            ->with('success', '申込を削除しました。');
    }
}
