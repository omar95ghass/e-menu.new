<?php

namespace App\Classes;

use PDO;

class Restaurant
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM restaurants WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $restaurant = $stmt->fetch();
        return $restaurant ?: null;
    }

    public function featured(int $limit = 6): array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, slug, city, cover_image FROM restaurants WHERE active = 1 ORDER BY created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search(array $filters): array
    {
        $query = 'SELECT * FROM restaurants WHERE active = 1';
        $params = [];

        if (!empty($filters['city'])) {
            $query .= ' AND city = :city';
            $params['city'] = $filters['city'];
        }
        if (!empty($filters['area'])) {
            $query .= ' AND area = :area';
            $params['area'] = $filters['area'];
        }
        if (!empty($filters['cuisine'])) {
            $query .= ' AND cuisine = :cuisine';
            $params['cuisine'] = $filters['cuisine'];
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getMenu(int $restaurantId): array
    {
        $categoriesStmt = $this->pdo->prepare('SELECT * FROM categories WHERE restaurant_id = :restaurant_id ORDER BY position');
        $categoriesStmt->execute(['restaurant_id' => $restaurantId]);
        $categories = $categoriesStmt->fetchAll();

        $itemsStmt = $this->pdo->prepare('SELECT * FROM menu_items WHERE restaurant_id = :restaurant_id ORDER BY category_id, position');
        $itemsStmt->execute(['restaurant_id' => $restaurantId]);
        $items = $itemsStmt->fetchAll();

        $menu = [];
        foreach ($categories as $category) {
            $menu[$category['id']] = $category;
            $menu[$category['id']]['items'] = [];
        }

        foreach ($items as $item) {
            if (!isset($menu[$item['category_id']])) {
                $menu[$item['category_id']] = ['items' => []];
            }
            $menu[$item['category_id']]['items'][] = $item;
        }

        return array_values($menu);
    }
}
