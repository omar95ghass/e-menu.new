<?php

namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\Database;
use App\Classes\Validator;
use function api_error;
use function api_success;

class AuthController
{
    private Auth $auth;
    private Validator $validator;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->auth = new Auth($pdo);
        $this->validator = new Validator();
    }

    public function login(array $input): array
    {
        if (!$this->validator->validate($input, ['email' => 'required|email', 'password' => 'required'])) {
            return api_error('Validation failed', 422, $this->validator->errors());
        }

        if (!$this->auth->attempt($input['email'], $input['password'])) {
            return api_error('Invalid credentials', 401);
        }

        return api_success(['user' => $_SESSION['user']]);
    }

    public function register(array $input): array
    {
        $rules = [
            'name' => 'required|max:120',
            'email' => 'required|email',
            'password' => 'required',
            'restaurant_name' => 'required',
            'slug' => 'required',
            'subdomain' => 'required',
        ];

        if (!$this->validator->validate($input, $rules)) {
            return api_error('Validation failed', 422, $this->validator->errors());
        }

        $id = $this->auth->registerRestaurant($input);
        return api_success(['user_id' => $id], 'Registration successful, pending activation');
    }
}
