<?php

use App\Http\Controllers\UrlController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
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

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/urls', [AdminController::class, 'urls'])->name('admin.urls');;
    Route::get('/admin/ads', [AdminController::class, 'ads'])->name('admin.ads');;
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');;
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');;
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');;
    Route::post('/admin/reports', [AdminController::class, 'updateReports'])->name('admin.reports.update');;
    Route::post('/admin/qr', [AdminController::class, 'updateQRSettings'])->name('admin.qrsettings.update');;
    Route::post('/admin/ads', [AdminController::class, 'updateAds'])->name('admin.ads.update');;
    Route::get('/admin/qr', [AdminController::class, 'qrsettings'])->name('admin.qrsettings');;
    Route::get('/admin/qr/test.svg', [UrlController::class, 'generateTestQR'])
        ->where('code', '[a-zA-Z0-9_-]{4,8}');
    Route::get('/admin/password', [AdminController::class, 'password'])->name('admin.password');;
    Route::post('/admin/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');;
});

Route::get('/', function () {
    return view('shorten');
})->name('home');

Route::get('/details', function () {
    return view('details');
});

Route::get('/report', function () {
    return view('report');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('home');
})->name('logout');

Auth::routes();

Route::get('/qr/{code}.svg', [UrlController::class, 'generateQR'])
    ->where('code', '[a-zA-Z0-9_-]{4,8}');

Route::get('{segment}', [UrlController::class, 'handleSegment'])
    ->where('segment', '[a-zA-Z0-9_-]{4,8}');

