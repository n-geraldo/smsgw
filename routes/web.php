<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DmaConfigController;
use App\Http\Controllers\DmaMappingController;
use App\Http\Controllers\SmsGatewayController;
use App\Http\Controllers\SmsMonitorController;
use App\Http\Controllers\SmsScheduleController;
use App\Http\Controllers\SmsTemplateController;
use App\Http\Controllers\SmsTestController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.alt');

    Route::resource('templates', SmsTemplateController::class)->except(['show']);
    Route::resource('schedules', SmsScheduleController::class)->except(['show']);
    Route::resource('gateways', SmsGatewayController::class)->except(['show']);

    Route::get('/dma/config', [DmaConfigController::class, 'show'])->name('dma.config');
    Route::post('/dma/config/test', [DmaConfigController::class, 'test'])->name('dma.config.test');

    Route::get('/dma/mapping', [DmaMappingController::class, 'show'])->name('dma.mapping');
    Route::post('/dma/mapping', [DmaMappingController::class, 'update'])->name('dma.mapping.update');
    Route::post('/dma/mapping/test', [DmaMappingController::class, 'test'])->name('dma.mapping.test');

    Route::get('/sms/monitor', [SmsMonitorController::class, 'index'])->name('sms.monitor');
    Route::get('/sms/monitor/{job}', [SmsMonitorController::class, 'show'])->name('sms.monitor.show');

    Route::get('/sms/test', [SmsTestController::class, 'show'])->name('sms.test');
    Route::post('/sms/test', [SmsTestController::class, 'send'])->name('sms.test.send');
});
