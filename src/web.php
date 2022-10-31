<?php

use AsayHome\AsayComponents\Controllers\AlertsController;
use AsayHome\AsayComponents\Controllers\ChattingController;
use AsayHome\AsayComponents\Controllers\NotificationsController;
use AsayHome\AsayComponents\Controllers\RepliesController;
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
    Route::post('makeAlertMessageAsRead', [AlertsController::class, 'makeAlertMessageAsRead'])->name('makeAlertMessageAsRead');
    Route::post('deleteAlertMessage', [AlertsController::class, 'deleteAlertMessage'])->name('deleteAlertMessage');
});
Route::group([
    'prefix' => config('asay-components.routes.prefix') . '/replies',
    'as' => config('asay-components.routes.as') . '.replies.',
], function () {
    Route::post('store', [RepliesController::class, 'store'])->name('store');
    Route::post('getReplies', [RepliesController::class, 'getReplies'])->name('getReplies');
    Route::post('destroy', [RepliesController::class, 'destroy'])->name('destroy');
});
Route::group([
    'prefix' => config('asay-components.routes.prefix') . '/notifications',
    'as' => config('asay-components.routes.as') . '.notifications.',
], function () {
    Route::get('getNotificationsLogs', [NotificationsController::class, 'getNotificationsLogs'])->name('getNotificationsLogs');
});
