<?php

use Illuminate\Support\Facades\Route;

Route::get('/almacen', function () {
    return view('almacen.index');
    })->middleware(['auth', 'verified'])
    ->name('almacen');

Route::get('/despacho', function () {
    return view('despacho.index');
    })->middleware(['auth', 'verified'])
    ->name('despacho');

    Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
