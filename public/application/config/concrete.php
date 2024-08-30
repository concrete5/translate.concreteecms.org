<?php

return [
    'seo' => [
        'url_rewriting_all' => true
    ],
    'session' => [
        'max_lifetime' => (new DateTime('+2 days'))->getTimestamp() - time(), // Expire if not accessed for 2 full days
        'cookie' => [
            'cookie_secure' => true,
            'cookie_samesite' => 'None',
        ],
    ],
];
