<?php

use App\Classes\SubdomainResolver;
use App\Classes\APIResponse;

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        static $config;
        if (!$config) {
            $config = require __DIR__ . '/../../config/config.php';
        }
        $segments = explode('.', $key);
        $value = $config;
        foreach ($segments as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }
        return $value;
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}

if (!function_exists('__')) {
    function __(string $key, string $locale = null)
    {
        static $translations = [];
        $locale = $locale ?: ($_SESSION['lang'] ?? config('app.constants.DEFAULT_LANG'));
        if (!isset($translations[$locale])) {
            $langFile = __DIR__ . "/../../lang/{$locale}.php";
            if (!file_exists($langFile)) {
                $langFile = __DIR__ . '/../../lang/' . config('app.constants.DEFAULT_LANG') . '.php';
            }
            $translations[$locale] = require $langFile;
        }
        return $translations[$locale][$key] ?? $key;
    }
}

if (!function_exists('response_json')) {
    function response_json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

if (!function_exists('resolve_subdomain')) {
    function resolve_subdomain(): ?string
    {
        $resolver = new SubdomainResolver();
        return $resolver->getSubdomain();
    }
}

if (!function_exists('api_success')) {
    function api_success(array $payload = [], string $message = 'OK'): array
    {
        return APIResponse::success($payload, $message);
    }
}

if (!function_exists('api_error')) {
    function api_error(string $message, int $code = 400, array $errors = []): array
    {
        return APIResponse::error($message, $code, $errors);
    }
}
