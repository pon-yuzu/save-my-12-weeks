<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiagnosisController;
use App\Http\Controllers\Admin\MailTemplateController;
use App\Http\Controllers\Admin\SeminarApplicationController;
use App\Http\Controllers\Admin\SeminarSettingController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\UnsubscribeReasonController;
use Illuminate\Support\Facades\Route;

// 認証
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login']);

// 認証必須
Route::middleware('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // ダッシュボード
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // 登録者管理
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('admin.subscribers.index');
    Route::get('/subscribers/{subscriber}', [SubscriberController::class, 'show'])->name('admin.subscribers.show');

    // 診断結果
    Route::get('/diagnosis', [DiagnosisController::class, 'index'])->name('admin.diagnosis.index');
    Route::get('/diagnosis/{diagnosis}', [DiagnosisController::class, 'show'])->name('admin.diagnosis.show');

    // メールテンプレート
    Route::resource('mail-templates', MailTemplateController::class)->names('admin.mail-templates');

    // セミナー申込
    Route::get('/seminar-applications', [SeminarApplicationController::class, 'index'])->name('admin.seminar-applications.index');
    Route::get('/seminar-applications/{seminarApplication}', [SeminarApplicationController::class, 'show'])->name('admin.seminar-applications.show');
    Route::delete('/seminar-applications/{seminarApplication}', [SeminarApplicationController::class, 'destroy'])->name('admin.seminar-applications.destroy');

    // セミナー設定
    Route::get('/seminar-settings', [SeminarSettingController::class, 'edit'])->name('admin.seminar-settings.edit');
    Route::put('/seminar-settings', [SeminarSettingController::class, 'update'])->name('admin.seminar-settings.update');

    // 配信停止理由
    Route::get('/unsubscribe-reasons', [UnsubscribeReasonController::class, 'index'])->name('admin.unsubscribe-reasons.index');
});
