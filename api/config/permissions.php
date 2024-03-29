<?php
$user = [        // can *read* and *write* own resources
    'BadgesUsers' => ['edit'],
    'CounsellorsCounsulees' => ['add', 'delete'],
    'Users' => ['edit', 'index', 'login', 'forgotpass'],
    'Sadhanas' => ['add', 'edit', 'getConfig', 'journal', 'myjournal', 'mystat', 'liststat',  'view'],
];

$counsellor = array_merge($user,  ['Sadhanas' => [...$user['Sadhanas'], 'journal']]);

return [
    'Permissions' => [
        'admin' => '*',       // `access` is ignored, can *read* and *write* all resources
        'counsellor' => $counsellor,
        'user' => $user,
    ]
];
