<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RankController;
use App\Livewire\Grade;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    // admin
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/admin/golongan', Grade::class)->name('golongan.index');
    Route::prefix("admin")->group(function () {
        Route::resources([
            'jabatan' => PositionController::class,
            'pangkat' => RankController::class,
            // 'golongan' => GradeController::class
        ]);
    });

    Route::get('/homepage', [PegawaiController::class, 'index'])->name('pegawai.homepage');
});

require __DIR__.'/settings.php';
