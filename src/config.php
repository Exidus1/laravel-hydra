<?php

return [

    'models' => [
        'user' => App\User::class,
    ],

    'passport' => [
        'extras' => [
            'input' => 'username',
            'field' => 'email',
            'default' => [
                'id',
                'email',
                'name',
            ]
        ]
    ]

];
