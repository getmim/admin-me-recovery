<?php

return [
    '__name' => 'admin-me-recovery',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/admin-me-recovery.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/admin-me-recovery' => ['install','update','remove'],
        'theme/admin/me/recovery' => ['install','update','remove'],
        'theme/mailer/me/recovery' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'admin' => NULL
            ],
            [
                'lib-user' => NULL
            ],
            [
                'lib-mailer' => NULL
            ]
        ],
        'optional' => [
            [
                'lib-user-main-email' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'AdminMeRecovery\\Model' => [
                'type' => 'file',
                'base' => 'modules/admin-me-recovery/model'
            ],
            'AdminMeRecovery\\Controller' => [
                'type' => 'file',
                'base' => 'modules/admin-me-recovery/controller'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'admin' => [
            'adminMeRecovery' => [
                'path' => [
                    'value' => '/me/recovery'
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminMeRecovery\\Controller\\Recovery::index'
            ],
            'adminMeRecoveryReset' => [
                'path' => [
                    'value' => '/me/recovery/(:token)',
                    'params' => [
                        'token' => 'any'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminMeRecovery\\Controller\\Recovery::reset'
            ]
        ]
    ],
    'admin' => [
        'login' => [
            'recovery' => ['adminMeRecovery',[],[]]
        ]
    ],
    'libForm' => [
        'forms' => [
            'admin.me.recovery' => [
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'nolabel' => true,
                    'rules' => [
                        'required' => true,
                        'email' => true
                    ]
                ]
            ],
            'admin.me.recovery.reset' => [
                'new-password' => [
                    'label' => 'New Password',
                    'nolabel' => true,
                    'type' => 'password',
                    'meter' => true,
                    'rules' => [
                        'required' => true,
                        'empty' => false,
                        'length' => [
                            'min' => 6
                        ]
                    ]
                ],
                'retype-password' => [
                    'label' => 'Retype Password',
                    'type' => 'password',
                    'nolabel' => true,
                    'rules' => [
                        'required' => true 
                    ]
                ]
            ]
        ]
    ]
];