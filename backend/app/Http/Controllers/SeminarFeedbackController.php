<?php

namespace App\Http\Controllers;

use App\Models\SeminarFeedback;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SeminarFeedbackController extends Controller
{
    public function show(string $token): View|RedirectResponse
    {
        $feedback = SeminarFeedback::where('token', $token)
            ->with(['application', 'seminar'])
            ->first();

        if (!$feedback) {
            abort(404, 'アンケートが見つかりません');
        }

        // 既に回答済みの場合
        if ($feedback->overall_rating !== null) {
            return view('seminar.feedback-complete', [
                'feedback' => $feedback,
            ]);
        }

        return view('seminar.feedback', [
            'feedback' => $feedback,
        ]);
    }

    public function submit(Request $request, string $token): RedirectResponse
    {
        $feedback = SeminarFeedback::where('token', $token)->first();

        if (!$feedback) {
            abort(404, 'アンケートが見つかりません');
        }

        $validated = $request->validate([
            'overall_rating' => 'required|integer|min:1|max:5',
            'content_rating' => 'required|integer|min:1|max:5',
            'most_helpful' => 'nullable|string|max:1000',
            'improvement_suggestions' => 'nullable|string|max:1000',
            'interested_in_program' => 'boolean',
            'interested_in_session' => 'boolean',
            'questions' => 'nullable|string|max:1000',
        ]);

        $validated['interested_in_program'] = $request->boolean('interested_in_program');
        $validated['interested_in_session'] = $request->boolean('interested_in_session');

        $feedback->update($validated);

        return redirect()->route('seminar.feedback.complete', ['token' => $token]);
    }

    public function complete(string $token): View
    {
        $feedback = SeminarFeedback::where('token', $token)->first();

        if (!$feedback) {
            abort(404);
        }

        return view('seminar.feedback-complete', [
            'feedback' => $feedback,
        ]);
    }
}
