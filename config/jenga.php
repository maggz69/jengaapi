<?php

return [
    'base_url' => 'https://uat.jengahq.io/',
    'username' => env('JENGA_USERNAME'),
    'password' => env('JENGA_PASSWORD'),
    'header'   => env('JENGA_HEADER'),

    'certificates' => [
        'local' => true,
        'path'  => '',
    ],
    'account' => [
        'country'    => 'KE',
        'account_id' => env('JENGA_ACCOUNT_ID'),
    ],
];
