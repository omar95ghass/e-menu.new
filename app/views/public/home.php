<?php
require_once __DIR__ . '/../../helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang'] ?? config('app.constants.DEFAULT_LANG')); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __('home.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/">e-menu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a class="nav-link" href="/"><?php echo __('nav.home'); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="/search"><?php echo __('nav.search'); ?></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?php echo __('nav.language'); ?></a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                        <li><a class="dropdown-item" href="?lang=en">English</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-slate-800 mb-3"><?php echo __('home.title'); ?></h1>
                <p class="lead text-muted mb-4"><?php echo __('home.subtitle'); ?></p>
                <div class="d-flex gap-3">
                    <a href="/login" class="btn btn-primary btn-lg shadow"><?php echo __('home.cta_login'); ?></a>
                    <a href="/register" class="btn btn-outline-secondary btn-lg"><?php echo __('home.cta_register'); ?></a>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="bg-white rounded-4 shadow-lg p-4">
                    <h2 class="h4 mb-3 text-slate-700"><?php echo __('home.featured_restaurants'); ?></h2>
                    <div class="row g-3">
                        <?php foreach (($featured ?? []) as $restaurant): ?>
                            <div class="col-6">
                                <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 class="h6 mb-1 text-slate-800"><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                                        <p class="text-muted small mb-0"><?php echo htmlspecialchars($restaurant['city'] ?? ''); ?></p>
                                    </div>
                                    <a href="/<?php echo htmlspecialchars($restaurant['slug']); ?>" class="mt-3 btn btn-sm btn-outline-primary">عرض</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($featured)): ?>
                            <div class="col-12 text-center text-muted">No restaurants yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
