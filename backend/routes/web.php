<?php

use App\Http\Controllers\MailTrackingController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\UnsubscribeController;
use Illuminate\Support\Facades\Route;

// メインの診断ページ
Route::get('/', function () {
    return view('diagnosis');
})->name('diagnosis');

// セミナー申込フォーム
Route::get('/seminar', [SeminarController::class, 'showForm'])->name('seminar.form');
Route::post('/seminar', [SeminarController::class, 'submit'])->name('seminar.submit');
Route::get('/seminar/complete', [SeminarController::class, 'complete'])->name('seminar.complete');

// 配信停止
Route::get('/unsubscribe/{token}', [UnsubscribeController::class, 'show'])->name('unsubscribe.show');
Route::post('/unsubscribe/{token}', [UnsubscribeController::class, 'process'])->name('unsubscribe.process');
Route::get('/unsubscribe-complete', [UnsubscribeController::class, 'complete'])->name('unsubscribe.complete');

// メール開封トラッキング
Route::get('/t/{token}', [MailTrackingController::class, 'pixel'])->name('mail.track');
