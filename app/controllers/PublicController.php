<?php

namespace App\Controllers;

use App\Classes\Database;
use App\Classes\Restaurant;
use App\Classes\SubdomainResolver;

class PublicController
{
    private Restaurant $restaurant;
    private SubdomainResolver $resolver;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->restaurant = new Restaurant($pdo);
        $this->resolver = new SubdomainResolver();
    }

    public function home(): array
    {
        $featured = $this->restaurant->featured();
        return ['featured' => $featured];
    }

    public function search(array $filters): array
    {
        $results = $this->restaurant->search($filters);
        return ['results' => $results];
    }

    public function restaurantProfile(string $slug): ?array
    {
        $restaurant = $this->restaurant->findBySlug($slug);
        if (!$restaurant) {
            return null;
        }
        $restaurant['menu'] = $this->restaurant->getMenu((int)$restaurant['id']);
        return $restaurant;
    }

    public function resolveSubdomainRestaurant(): ?array
    {
        $subdomain = $this->resolver->getSubdomain();
        if (!$subdomain) {
            return null;
        }
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM restaurants WHERE subdomain = :subdomain AND active = 1 LIMIT 1');
        $stmt->execute(['subdomain' => $subdomain]);
        $restaurant = $stmt->fetch();
        if (!$restaurant) {
            return null;
        }
        $restaurant['menu'] = $this->restaurant->getMenu((int)$restaurant['id']);
        return $restaurant;
    }
}
