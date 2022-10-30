<?php

use AsayHome\AsayComponents\Controllers\ChattingController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('asay-components.routes.prefix') . '/chattings',
    'as' => config('asay-components.routes.as') . '.chattings',
], function () {
    Route::post('getMessages', [ChattingController::class, 'getMessages'])->name('getMessages');
    Route::post('getUsers', [ChattingController::class, 'getUsers'])->name('getUsers');
    Route::post('sendMessage', [ChattingController::class, 'sendMessage'])->name('sendMessage');
});
