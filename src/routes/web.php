<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\TimerController;

// タイマー画面
Route::middleware('auth')->get('/time-entries/timer', function () {
    return view('time-entries.timer');
})->name('time-entries.timer');

// タイマー機能
Route::middleware('auth')->group(function () {
    Route::post('/timer/start', [TimerController::class, 'start'])->name('timer.start');
    Route::post('/timer/stop/{timeEntry}', [TimerController::class, 'stop'])->name('timer.stop');
});

// 日次工数入力画面
Route::middleware('auth')->get('/time-entries/daily', function () {
    return view('time-entries.daily');
})->name('time-entries.daily');

// 日次工数入力登録
Route::middleware('auth')->post('/time-entries/daily', [TimeEntryController::class, 'storeDaily'])->name('time-entries.daily.store');
// 工数集計画面
Route::middleware('auth')->get('/time-entries/summary', [TimeEntryController::class, 'summary'])->name('time-entries.summary');

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('clients', ClientController::class);
    // タスク担当者アサイン
    Route::post('/task-assignments', [TaskAssignmentController::class, 'store'])->name('task-assignments.store');
    Route::delete('/task-assignments/{taskAssignment}', [TaskAssignmentController::class, 'destroy'])->name('task-assignments.destroy');
    // 工数記録
    Route::resource('time-entries', TimeEntryController::class);
});

// 案件管理・タスク管理（一覧・詳細は誰でも可、登録・編集・削除は認証ユーザーのみ）
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);

require __DIR__.'/auth.php';

// レポート機能
use App\Http\Controllers\ReportController;
Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
    Route::get('/project', [ReportController::class, 'project'])->name('project');
    Route::get('/member', [ReportController::class, 'member'])->name('member');
    Route::get('/export', [ReportController::class, 'export'])->name('export');
});