<?php
return [
    'Permissions' => [
        'admin' => '*',       // `access` is ignored, can *read* and *write* all resources
        'user' => [        // can *read* and *write* own resources
            'Users' => ['login'],
        ],
    ]
];
