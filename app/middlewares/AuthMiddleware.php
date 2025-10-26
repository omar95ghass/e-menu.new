<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public static function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            exit;
        }
    }
}
