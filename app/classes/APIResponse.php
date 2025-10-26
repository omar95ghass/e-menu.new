<?php

namespace App\Classes;

class APIResponse
{
    public static function success(array $data = [], string $message = 'OK'): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function error(string $message, int $code = 400, array $errors = []): array
    {
        return [
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
        ];
    }
}
