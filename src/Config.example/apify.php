<?php

return [
    'enable' => true,
    'logging' => true,    
    'raw_query_enable' => true,
    'api_token_field' => 'api_token',
    'auth' => 'basic',
    'middlewares' => ['basic'],
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
