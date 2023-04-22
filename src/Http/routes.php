<?php

use DrH\Buni\Http\Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('/buni')
    ->name('buni.')
    ->group(function () {
        Route::post('/stk-callback', [Controller::class, 'handleStkCallback'])->name('stk.callback');
    });
