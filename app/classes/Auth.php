<?php

namespace App\Classes;

use PDO;
use Exception;

class Auth
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function attempt(string $email, string $password): bool
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'role' => $user['role'],
            'restaurant_id' => $user['restaurant_id'] ?? null,
            'plan_id' => $user['plan_id'] ?? null,
        ];

        $this->loadPermissions();

        return true;
    }

    public function registerRestaurant(array $data): int
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
            $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'role' => 'restaurant',
            ]);
            $userId = (int)$this->pdo->lastInsertId();

            $restaurantStmt = $this->pdo->prepare('INSERT INTO restaurants (user_id, name, slug, active, subdomain) VALUES (:user_id, :name, :slug, 0, :subdomain)');
            $restaurantStmt->execute([
                'user_id' => $userId,
                'name' => $data['restaurant_name'],
                'slug' => $data['slug'],
                'subdomain' => $data['subdomain'],
            ]);

            $this->pdo->commit();
            return $userId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public static function checkRole(string $role): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            exit;
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    private function loadPermissions(): void
    {
        if (!isset($_SESSION['user']['plan_id'])) {
            return;
        }
        $stmt = $this->pdo->prepare('SELECT * FROM plans WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user']['plan_id']]);
        $plan = $stmt->fetch();
        $_SESSION['user']['permissions'] = $plan ?: [];
    }
}
