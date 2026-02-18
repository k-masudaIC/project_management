<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// クライアント管理
Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class);
});

// 案件管理（一覧・詳細は誰でも可、登録・編集・削除は認証ユーザーのみ）
Route::resource('projects', ProjectController::class);

// タスク管理（一覧・詳細は誰でも可、登録・編集・削除は認証ユーザーのみ）
Route::resource('tasks', App\Http\Controllers\TaskController::class);

require __DIR__.'/auth.php';
