<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\AuthController;

$controller = new AuthController();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

switch ($method) {
    case 'POST':
        $action = $_GET['action'] ?? '';
        if ($action === 'login') {
            $response = $controller->login($input);
            $statusCode = ($response['status'] ?? '') === 'error' ? ($response['code'] ?? 400) : 200;
            response_json($response, $statusCode);
        }
        if ($action === 'register') {
            $response = $controller->register($input);
            $statusCode = ($response['status'] ?? '') === 'error' ? ($response['code'] ?? 400) : 201;
            response_json($response, $statusCode);
        }
        break;
}

response_json(api_error('Invalid request', 400), 400);
