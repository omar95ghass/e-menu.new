<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\RestaurantController;
use App\Middlewares\AuthMiddleware;

AuthMiddleware::requireAuth();

$controller = new RestaurantController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (($_GET['action'] ?? '') === 'dashboard') {
            response_json(api_success($controller->dashboard()));
        }
        break;
    case 'POST':
        if (($_GET['action'] ?? '') === 'upload-image' && isset($_FILES['image'])) {
            $response = $controller->uploadImage($_FILES['image']);
            $statusCode = ($response['status'] ?? '') === 'error' ? ($response['code'] ?? 400) : 200;
            response_json($response, $statusCode);
        }
        break;
}

response_json(api_error('Invalid request', 400), 400);
