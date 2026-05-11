<?php

return [
    'debug' => false,
    'url'   => 'https://reacien.dev',

    'panel' => [
        'install' => false,
    ],

    'cache' => [
        'pages' => [
            'active' => true,
            'ignore' => function ($page) {
                return $page->template()->name() === 'error';
            },
        ],
    ],
];
