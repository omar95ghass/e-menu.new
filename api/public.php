<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\PublicController;

$controller = new PublicController();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'search') {
        $filters = [
            'city' => $_GET['city'] ?? null,
            'area' => $_GET['area'] ?? null,
            'cuisine' => $_GET['cuisine'] ?? null,
        ];
        $response = api_success($controller->search($filters));
        response_json($response);
    }
    if ($action === 'restaurant' && isset($_GET['slug'])) {
        $restaurant = $controller->restaurantProfile($_GET['slug']);
        if (!$restaurant) {
            response_json(api_error('Restaurant not found', 404), 404);
        }
        $response = api_success(['restaurant' => $restaurant]);
        response_json($response);
    }
}

if ($method === 'POST') {
    $action = $_GET['action'] ?? '';
    if ($action === 'review' && isset($_GET['slug'])) {
        // Basic stub - insert review logic
        $response = api_success([], 'Review submitted');
        response_json($response, 201);
    }
}

response_json(api_error('Invalid request', 400), 400);
