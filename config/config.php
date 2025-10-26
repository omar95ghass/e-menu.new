<?php

$constants = require __DIR__ . '/constants.php';

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'e_menu',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_url' => getenv('APP_URL') ?: 'http://e-menu.local',
        'env' => getenv('APP_ENV') ?: 'development',
        'constants' => $constants,
    ],
];
