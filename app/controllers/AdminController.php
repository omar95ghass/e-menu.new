<?php

namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\Database;
use App\Classes\PlanManager;

class AdminController
{
    private $pdo;
    private PlanManager $plans;

    public function __construct()
    {
        Auth::checkRole('superuser');
        $this->pdo = Database::getConnection();
        $this->plans = new PlanManager($this->pdo);
    }

    public function index(): array
    {
        $stats = $this->pdo->query('SELECT COUNT(*) as restaurants FROM restaurants')->fetch();
        return ['stats' => $stats];
    }
}
