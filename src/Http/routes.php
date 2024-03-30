<?php

use DrH\Buni\Http\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('buni/callbacks')
    ->name('buni.callbacks')
    ->group(function () {
        Route::post('stk', [Controller::class, 'handleStkCallback'])->name('stk');
        Route::post('ipn', [Controller::class, 'handleIpn'])->name('ipn');
    });
