<?php

use App\Http\Controllers\Api\DiagnosisController;
use App\Models\Seminar;
use Illuminate\Support\Facades\Route;

Route::post('/diagnosis', [DiagnosisController::class, 'store']);
Route::post('/newsletter/subscribe', [DiagnosisController::class, 'subscribe']);

// 直近のセミナー情報を取得
Route::get('/seminar/upcoming', function () {
    $seminar = Seminar::upcoming();

    if (!$seminar) {
        return response()->json(['seminar' => null]);
    }

    return response()->json([
        'seminar' => [
            'id' => $seminar->id,
            'title' => $seminar->title,
            'scheduled_at' => $seminar->scheduled_at->toIso8601String(),
            'formatted_schedule' => $seminar->formatted_schedule,
            'is_full' => $seminar->is_full,
        ]
    ]);
});
