<?php

use App\Http\Controllers\Api\DiagnosisController;
use Illuminate\Support\Facades\Route;

Route::post('/diagnosis', [DiagnosisController::class, 'store']);
Route::post('/newsletter/subscribe', [DiagnosisController::class, 'subscribe']);
