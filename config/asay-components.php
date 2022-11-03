<?php

use App\Models\User;

return [
    'userModelInstance' => User::class,
    'admin_users_ids' => [1],
    'account_statuses' => [
        'field_name' => 'active',
        // 'statuses' => 
    ],
    'routes' => [
        'prefix' => '',
        'as' => 'admin',
    ],
];
