<?php

use Codeplx\LaravelCodeplxSupport\Http\Controllers\CodeplxSupportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'web'])->group(function () {
    Route::get('codeplx/support', [CodeplxSupportController::class, 'index'])->name('codeplx.support.index');
    Route::post('codeplx/support', [CodeplxSupportController::class, 'store'])->name('codeplx.support.store');
});
