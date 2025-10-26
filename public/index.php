<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\PublicController;

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

$public = new PublicController();

// Subdomain handling
if ($uri === '/' && ($subRestaurant = $public->resolveSubdomainRestaurant())) {
    $restaurant = $subRestaurant;
    include __DIR__ . '/../app/views/public/restaurant.php';
    exit;
}

switch ($uri) {
    case '/':
        $data = $public->home();
        $featured = $data['featured'];
        include __DIR__ . '/../app/views/public/home.php';
        break;
    case '/search':
        $results = [];
        include __DIR__ . '/../app/views/public/search.php';
        break;
    default:
        $slug = ltrim($uri, '/');
        $restaurant = $public->restaurantProfile($slug);
        if ($restaurant) {
            include __DIR__ . '/../app/views/public/restaurant.php';
        } else {
            http_response_code(404);
            echo 'Not Found';
        }
        break;
}
