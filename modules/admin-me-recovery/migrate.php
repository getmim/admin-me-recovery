<?php

return [
    'AdminMeRecovery\\Model\\UserRecovery' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => TRUE,
                    'primary_key' => TRUE,
                    'auto_increment' => TRUE
                ],
                'index' => 1000
            ],
            'user' => [
                'type' => 'INT',
                'attrs' => [
                    'null' => false,
                    'unsigned' => true 
                ],
                'index' => 2000
            ],
            'hash' => [
                'type' => 'VARCHAR',
                'length' => 100,
                'attrs' => [
                    'null' => false,
                    'unique' => true
                ],
                'index' => 3000
            ],
            'expires' => [
                'type' => 'DATETIME',
                'attrs' => [],
                'index' => 4000
            ],
            'updated' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP',
                    'update' => 'CURRENT_TIMESTAMP'
                ],
                'index' => 9000
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ],
                'index' => 10000
            ]
        ]
    ]
];