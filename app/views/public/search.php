<?php
require_once __DIR__ . '/../../helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang'] ?? config('app.constants.DEFAULT_LANG')); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __('search.title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
<div class="container py-5">
    <h1 class="mb-4 text-slate-800 fw-bold"><?php echo __('search.title'); ?></h1>
    <form class="row g-3 mb-4" id="search-form">
        <div class="col-md-3">
            <label class="form-label"><?php echo __('search.city'); ?></label>
            <input type="text" class="form-control" name="city">
        </div>
        <div class="col-md-3">
            <label class="form-label"><?php echo __('search.area'); ?></label>
            <input type="text" class="form-control" name="area">
        </div>
        <div class="col-md-3">
            <label class="form-label"><?php echo __('search.cuisine'); ?></label>
            <input type="text" class="form-control" name="cuisine">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary w-100">بحث</button>
        </div>
    </form>
    <div id="search-results" class="row g-3"></div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function(){
    $('#search-form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '/api/public.php',
            method: 'GET',
            data: $(this).serialize() + '&action=search',
            dataType: 'json',
            success: function(resp){
                const container = $('#search-results');
                container.empty();
                if(resp.data && resp.data.results){
                    resp.data.results.forEach(function(item){
                        container.append(`
                            <div class="col-md-4">
                                <div class="bg-white shadow-sm rounded-3 p-3 h-100">
                                    <h2 class="h5 text-slate-800">${item.name}</h2>
                                    <p class="text-muted small mb-2">${item.city ?? ''}</p>
                                    <a class="btn btn-outline-primary btn-sm" href="/${item.slug}">عرض</a>
                                </div>
                            </div>
                        `);
                    });
                }
            }
        });
    });
});
</script>
</body>
</html>
