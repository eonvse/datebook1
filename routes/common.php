<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;

Route::middleware('auth')->group(function () {
    Route::get('/start', [CommonController::class, 'start_page'])->name('start');
    Route::get('/team/join', [CommonController::class, 'team_join'])->name('team_join');
    Route::put('/team/current/update', [CommonController::class, 'current_team_update'])->name('current-team.update');

    Route::get('/category', [CommonController::class, 'category_materials'])->name('materials.category');

});
