<?php

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

require_once __DIR__ . '/helpers/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang']) && in_array($_GET['lang'], config('app.constants.SUPPORTED_LANGS'), true)) {
    $_SESSION['lang'] = $_GET['lang'];
}
