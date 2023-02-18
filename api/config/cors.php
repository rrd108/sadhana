<?php
return [
    'Cors' => [
        'AllowOrigin' => ['http://localhost:5173', 'https://sadhana.1108.cc'],
        'AllowMethods' => ['GET', 'POST', 'PATCH', 'OPTIONS'],
        'AllowHeaders' => ['Token', 'Content-Type'],
        'MaxAge' => 300,
    ]
];
