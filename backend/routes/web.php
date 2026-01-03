<?php

use Illuminate\Support\Facades\Route;

// メインの診断ページ
Route::get('/', function () {
    return view('diagnosis');
})->name('diagnosis');

// SPAのためのフォールバックルート（必要に応じて）
Route::get('/{any}', function () {
    return view('diagnosis');
})->where('any', '.*')->name('spa.fallback');
