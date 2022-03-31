<?php

return [
    'enable' => true,
    'logging' => true,
    'api_token_field' => 'api_token',
    'auth' => 'basic',
    'basicAuthentication' => [
        'username' => 'johndoe',
        'password' => 'exampl3@@'
    ],
    'users' => [
        [
            'token' => 'full',
            'permissions' => [
                '*' => ['create', 'read', 'update', 'delete', 'raw']
            ]
        ],[
            'token' => 'read',
            'permissions' => [
                '*' => ['read']
            ]
        ],
        [
            'token' => '',
            'permissions' => [

            ]
        ]
    ]
];
