<?php
$user = [        // can *read* and *write* own resources
    'BadgesUsers' => ['edit'],
    'Users' => ['login', 'forgotpass'],
    'Sadhanas' => ['view', 'add', 'edit', 'mystat', 'liststat'],
];

$counsellor = array_merge($user,  ['Sadhanas' => [...$user['Sadhanas'], 'journal']]);

return [
    'Permissions' => [
        'admin' => '*',       // `access` is ignored, can *read* and *write* all resources
        'counsellor' => $counsellor,
        'user' => $user,
    ]
];
