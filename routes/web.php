<?php

use App\Http\Controllers\AttendanceReportController;
use Illuminate\Support\Facades\Route;

Route::get('/icon.png', function () {
    $path = file_exists(public_path('icon.png'))
        ? public_path('icon.png')
        : base_path('icon.png');

    return response()->file($path, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('favicon.png');

Route::get('/favicon.ico', function () {
    $path = file_exists(public_path('icon.png'))
        ? public_path('icon.png')
        : base_path('icon.png');

    return response()->file($path, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('favicon.ico');

Route::redirect('/', '/admin');
Route::redirect('/dasbor', '/admin');
Route::redirect('/pegawai', '/admin/pegawai');
Route::redirect('/perbaikan', '/admin/perbaikan');
Route::redirect('/laporan', '/admin/laporan');
Route::redirect('/media', '/admin/media');
Route::redirect('/pengaturan', '/admin/pengaturan');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/perbaikan/{report}/unduh-final', [AttendanceReportController::class, 'downloadFinal'])
        ->name('perbaikan.download-final');

    Route::get('/perbaikan/{report}/unduh-draf', [AttendanceReportController::class, 'downloadDraft'])
        ->name('perbaikan.download-draft');
});
