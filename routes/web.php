<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Almacen\LotesTable;

Route::get('/almacen', function () {
    return view('almacen.index');
    })->name('almacen');

// Route::get('/almacen', LotesTable::class());

Route::get('/despacho', function () {
    return view('despacho.index');
    })->name('despacho');

    Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    // Route::get('/archivo', function () {
    //     return view('archivo.archivo');
    //     })->name('archivo');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
