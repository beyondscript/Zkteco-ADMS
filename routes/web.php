<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\ZktecoController;

Route::get('/', [FrontendController::class, 'index'])->name('index');

Route::middleware(['auth'])->group(function () {
	Route::get('/devices', [BackendController::class, 'index'])->name('dashboard');

	Route::get('/attendances', [BackendController::class, 'attendances'])->name('attendances');
	Route::delete('/reset-attendances', [BackendController::class, 'resetAttendances'])->name('resetAttendances');

	Route::get('/error-logs', [BackendController::class, 'errorLogs'])->name('errorLogs');
	Route::delete('/reset-error-logs', [BackendController::class, 'resetErrorLogs'])->name('resetErrorLogs');

	Route::get('/change-email', [BackendController::class, 'changeEmail'])->name('changeEmail');
	Route::post('/update-email', [BackendController::class, 'updateEmail'])->name('updateEmail');

	Route::get('/change-password', [BackendController::class, 'changePassword'])->name('changePassword');
	Route::post('/update-password', [BackendController::class, 'updatePassword'])->name('updatePassword');
});

Route::get('/iclock/cdata', [ZktecoController::class, 'handshake']);
Route::post('/iclock/cdata', [ZktecoController::class, 'receiveRecords']);
