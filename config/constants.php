<?php

return [
    'PAGINATE' => [
        'DEFAULT_PAGE' => 1,
        'DEFAULT_PER_PAGE' => 15,
        'DEFAULT_ORDER_BY' => 'created_at',
        'DEFAULT_DIRECTION' => 'DESC',
        'DEFAULT_PAGINATION' => 1,
    ],
    'MEDIA' => [
        'IMAGE' => [
            'MAX_SIZE' => 2048,
            'MIMES' => 'jpeg,png,jpg,gif,svg',
            'PATH' => 'public',
            'DISK' => 'public',
        ],
    ],
    'MEDIA_PATH' => env('APP_URL'),
];