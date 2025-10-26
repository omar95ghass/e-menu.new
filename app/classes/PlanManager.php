<?php

namespace App\Classes;

use PDO;

class PlanManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPlanForRestaurant(int $restaurantId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM plans p JOIN restaurants r ON r.subscription_plan_id = p.id WHERE r.id = :restaurant_id LIMIT 1');
        $stmt->execute(['restaurant_id' => $restaurantId]);
        $plan = $stmt->fetch();
        return $plan ?: null;
    }

    public function checkLimit(int $restaurantId, string $action, array $context = []): array
    {
        $plan = $this->getPlanForRestaurant($restaurantId);
        if (!$plan) {
            return ['ok' => false, 'message' => 'Plan not found'];
        }

        switch ($action) {
            case 'add_category':
                $count = $this->countCategories($restaurantId);
                if ($plan['max_categories'] !== null && $count >= (int)$plan['max_categories']) {
                    return ['ok' => false, 'message' => 'Category limit reached'];
                }
                break;
            case 'add_item':
                $count = $this->countItems($restaurantId);
                if ($plan['max_items'] !== null && $count >= (int)$plan['max_items']) {
                    return ['ok' => false, 'message' => 'Item limit reached'];
                }
                break;
            case 'add_option':
                $itemId = $context['item_id'] ?? 0;
                $count = $this->countOptions($itemId);
                if ($plan['max_options_per_item'] !== null && $count >= (int)$plan['max_options_per_item']) {
                    return ['ok' => false, 'message' => 'Option limit reached'];
                }
                break;
            case 'add_image':
                $itemId = $context['item_id'] ?? 0;
                $count = $this->countImages($itemId);
                if ($plan['max_images_per_item'] !== null && $count >= (int)$plan['max_images_per_item']) {
                    return ['ok' => false, 'message' => 'Image limit reached'];
                }
                break;
        }

        return ['ok' => true, 'plan' => $plan];
    }

    private function countCategories(int $restaurantId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM categories WHERE restaurant_id = :restaurant_id');
        $stmt->execute(['restaurant_id' => $restaurantId]);
        return (int)$stmt->fetchColumn();
    }

    private function countItems(int $restaurantId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM menu_items WHERE restaurant_id = :restaurant_id');
        $stmt->execute(['restaurant_id' => $restaurantId]);
        return (int)$stmt->fetchColumn();
    }

    private function countOptions(int $itemId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM menu_item_options WHERE menu_item_id = :menu_item_id');
        $stmt->execute(['menu_item_id' => $itemId]);
        return (int)$stmt->fetchColumn();
    }

    private function countImages(int $itemId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM menu_item_images WHERE menu_item_id = :menu_item_id');
        $stmt->execute(['menu_item_id' => $itemId]);
        return (int)$stmt->fetchColumn();
    }
}
