<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/chart/data', [DashboardController::class, 'chartData']);

Route::get('/chart/sensor', [DashboardController::class, 'conditionData']);

Route::get('/chart/latestData', [DashboardController::class, 'latestData']);

Route::get('/status', [DashboardController::class, 'status'])->name('dashboard-status');
Route::get('/settings', [DashboardController::class, 'settings'])->name('dashboard-settings');

