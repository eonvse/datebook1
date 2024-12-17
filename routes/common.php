<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;

Route::middleware('auth')->group(function () {
    Route::get('/team/join', [CommonController::class, 'team_join'])->name('team_join');
});
