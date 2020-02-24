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
            ],
        ],
    ],

    'language' => [
        'header' => 'Hydra-Lang',
        'param' => 'lang',
    ],

];
