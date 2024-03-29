<?php

return [

    /*
    |----------------------------------------------------------------------
    | Chunk Limit
    |----------------------------------------------------------------------
    |
    | To avoid failure during bulk update on a huge record, database query
    | is separated into smaller chunk.
    |
    */

    'chunk' => 100,

    /*
    |----------------------------------------------------------------------
    | Driver Configuration
    |----------------------------------------------------------------------
    |
    | Setup your driver configuration to let us match the driver name to
    | a Model and path to migration.
    |
    */

    'drivers' => [
        'account' => [
            'model'  => 'App\Account',
            'path'   => database_path('accounts'),
            'shared' => false,
        ],
    ],
];
