<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiagnosisController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\MailTemplateController;
use App\Http\Controllers\Admin\SeminarApplicationController;
use App\Http\Controllers\Admin\SeminarSettingController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\UnsubscribeReasonController;
use Illuminate\Support\Facades\Route;

// 認証（ゲスト）
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login']);

// 招待リンクからの登録（ゲスト）
Route::get('/register/{token}', [AuthController::class, 'showRegisterForm'])->name('admin.register.form');
Route::post('/register/{token}', [AuthController::class, 'register'])->name('admin.register');

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

    // ブロードキャスト（一斉・個別メール）
    Route::get('/broadcasts/preview', [BroadcastController::class, 'preview'])->name('admin.broadcasts.preview');
    Route::resource('broadcasts', BroadcastController::class)->names('admin.broadcasts');

    // セミナー申込
    Route::get('/seminar-applications', [SeminarApplicationController::class, 'index'])->name('admin.seminar-applications.index');
    Route::get('/seminar-applications/{seminarApplication}', [SeminarApplicationController::class, 'show'])->name('admin.seminar-applications.show');
    Route::delete('/seminar-applications/{seminarApplication}', [SeminarApplicationController::class, 'destroy'])->name('admin.seminar-applications.destroy');

    // セミナー設定
    Route::get('/seminar-settings', [SeminarSettingController::class, 'edit'])->name('admin.seminar-settings.edit');
    Route::put('/seminar-settings', [SeminarSettingController::class, 'update'])->name('admin.seminar-settings.update');

    // 配信停止理由
    Route::get('/unsubscribe-reasons', [UnsubscribeReasonController::class, 'index'])->name('admin.unsubscribe-reasons.index');

    // 管理者専用機能
    Route::middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
        // 招待管理
        Route::get('/invitations', [InvitationController::class, 'index'])->name('admin.invitations.index');
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('admin.invitations.create');
        Route::post('/invitations', [InvitationController::class, 'store'])->name('admin.invitations.store');
        Route::get('/invitations/{invitation}', [InvitationController::class, 'show'])->name('admin.invitations.show');
        Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('admin.invitations.destroy');
    });
});
