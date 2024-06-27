<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/chart/data', [DashboardController::class, 'chartData'])->middleware('auth');
Route::get('/chart/sensor', [DashboardController::class, 'conditionData'])->middleware('auth');
Route::get('/chart/latestData', [DashboardController::class, 'latestData'])->middleware('auth');
Route::get('/chart/filterHistory', [DashboardController::class, 'filterHistory'])->name('filterHistory')->middleware('auth');

Route::get('/status', [DashboardController::class, 'status'])->name('dashboard-status')->middleware('auth');
Route::get('/settings', [DashboardController::class, 'settings'])->name('dashboard-settings')->middleware('auth');
Route::get('/settings/device/status', [DashboardController::class, 'deviceStatus'])->name('device-status')->middleware('auth');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/auth/{provider}/redirect', [LoginController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [LoginController::class, 'callback']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');