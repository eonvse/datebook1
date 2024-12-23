<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/debug', function () {
        return view('dashboard');
    })->name('debug');
});

require __DIR__.'/common.php';
require __DIR__.'/livewire.php';
