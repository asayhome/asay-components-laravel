<?php

use AsayHome\AsayComponents\Controllers\AlertsController;
use AsayHome\AsayComponents\Controllers\ChattingController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('asay-components.routes.prefix') . '/chattings',
    'as' => config('asay-components.routes.as') . '.chattings.',
], function () {
    Route::post('getMessages', [ChattingController::class, 'getMessages'])->name('getMessages');
    Route::post('getUsers', [ChattingController::class, 'getUsers'])->name('getUsers');
    Route::post('sendMessage', [ChattingController::class, 'sendMessage'])->name('sendMessage');
});
Route::group([
    'prefix' => config('asay-components.routes.prefix') . '/alerts',
    'as' => config('asay-components.routes.as') . '.alerts.',
], function () {
    Route::post('getConfig', [AlertsController::class, 'getConfig'])->name('getConfig');
    Route::post('getAlerts', [AlertsController::class, 'getAlerts'])->name('getAlerts');
    Route::post('getReceivers', [AlertsController::class, 'getReceivers'])->name('getReceivers');
    Route::post('sendAlert', [AlertsController::class, 'sendAlert'])->name('sendAlert');
});
Route::group([
    'prefix' => config('asay-components.routes.prefix') . '/replies',
    'as' => config('asay-components.routes.as') . '.replies.',
], function () {
    Route::post('getConfig', [AlertsController::class, 'getConfig'])->name('getConfig');
    Route::post('getAlerts', [AlertsController::class, 'getAlerts'])->name('getAlerts');
    Route::post('getReceivers', [AlertsController::class, 'getReceivers'])->name('getReceivers');
    Route::post('sendAlert', [AlertsController::class, 'sendAlert'])->name('sendAlert');
});
