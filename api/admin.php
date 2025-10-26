<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\AdminController;
use App\Classes\Auth;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Auth::checkRole('superuser');

$controller = new AdminController();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && ($_GET['action'] ?? '') === 'dashboard') {
    response_json(api_success($controller->index()));
}

response_json(api_error('Invalid request', 400), 400);
