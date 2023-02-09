<?php
return [
    'Cors' => [
        'AllowOrigin' => ['http://localhost:5173'],
        'AllowMethods' => ['GET', 'POST', 'PATCH', 'OPTIONS'],
        'AllowHeaders' => ['Token', 'Content-Type'],
        'MaxAge' => 300,
    ]
];
