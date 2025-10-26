<?php

namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\Database;
use App\Classes\PlanManager;
use App\Classes\FileUploader;
use App\Classes\Restaurant;
use function api_error;
use function api_success;
use function config;

class RestaurantController
{
    private $pdo;
    private PlanManager $plans;
    private Restaurant $restaurant;

    public function __construct()
    {
        Auth::checkRole('restaurant');
        $this->pdo = Database::getConnection();
        $this->plans = new PlanManager($this->pdo);
        $this->restaurant = new Restaurant($this->pdo);
    }

    public function dashboard(): array
    {
        $restaurantId = $_SESSION['user']['restaurant_id'];
        $visits = $this->pdo->prepare('SELECT COUNT(*) FROM interactions WHERE restaurant_id = :restaurant_id');
        $visits->execute(['restaurant_id' => $restaurantId]);
        $stats = [
            'visits' => (int)$visits->fetchColumn(),
        ];
        return ['stats' => $stats];
    }

    public function uploadImage(array $file): array
    {
        $restaurantId = $_SESSION['user']['restaurant_id'];
        $check = $this->plans->checkLimit($restaurantId, 'add_image', ['item_id' => $file['item_id'] ?? 0]);
        if (!$check['ok']) {
            return api_error($check['message']);
        }

        $uploader = new FileUploader(config('app.constants.UPLOAD_DIR'));
        $upload = $uploader->upload($file, $restaurantId);

        return api_success(['image' => $upload], 'Image uploaded');
    }
}
