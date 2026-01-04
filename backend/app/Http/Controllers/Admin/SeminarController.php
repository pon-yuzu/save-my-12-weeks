<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarController extends Controller
{
    public function index(): View
    {
        return view('admin.seminars.index');
    }

    /**
     * カレンダー用イベント取得API
     */
    public function events(Request $request): JsonResponse
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $seminars = Seminar::query()
            ->when($start, fn($q) => $q->where('scheduled_at', '>=', $start))
            ->when($end, fn($q) => $q->where('scheduled_at', '<=', $end))
            ->get();

        $events = $seminars->map(fn($s) => $s->toCalendarEvent());

        return response()->json($events);
    }

    /**
     * セミナー作成API
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
            'zoom_link' => 'nullable|url',
            'line_openchat_link' => 'nullable|url',
            'participation_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $validated['duration_minutes'] = $validated['duration_minutes'] ?? 120;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $seminar = Seminar::create($validated);

        return response()->json([
            'success' => true,
            'seminar' => $seminar,
            'event' => $seminar->toCalendarEvent(),
        ]);
    }

    /**
     * セミナー詳細取得API
     */
    public function show(Seminar $seminar): JsonResponse
    {
        return response()->json([
            'seminar' => $seminar,
            'formatted_schedule' => $seminar->formatted_schedule,
            'applications_count' => $seminar->applications_count,
            'is_full' => $seminar->is_full,
        ]);
    }

    /**
     * セミナー更新API
     */
    public function update(Request $request, Seminar $seminar): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'scheduled_at' => 'sometimes|required|date',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
            'zoom_link' => 'nullable|url',
            'line_openchat_link' => 'nullable|url',
            'participation_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $seminar->update($validated);

        return response()->json([
            'success' => true,
            'seminar' => $seminar->fresh(),
            'event' => $seminar->fresh()->toCalendarEvent(),
        ]);
    }

    /**
     * セミナー削除API
     */
    public function destroy(Seminar $seminar): JsonResponse
    {
        $seminar->delete();

        return response()->json(['success' => true]);
    }
}
