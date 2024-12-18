<?php

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/datebook1/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/datebook1/public/livewire/update', $handle)
        ->middleware(['auth:sanctum', 'verified']); 
});
