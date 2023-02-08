<?php
return [
    'Cors' => [
        'AllowOrigin' => ['http://localhost:5173'],
        'AllowMethods' => ['GET', 'POST', 'OPTIONS'],
        'AllowHeaders' => ['Token', 'Content-Type'],
        'MaxAge' => 300,
    ]
];
