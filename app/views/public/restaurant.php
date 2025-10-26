<?php
require_once __DIR__ . '/../../helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang'] ?? config('app.constants.DEFAULT_LANG')); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars(($restaurant['name'] ?? '') . ' - e-menu'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
<div class="container py-5">
    <div class="bg-white rounded-4 shadow-lg p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bold text-slate-800 mb-2"><?php echo htmlspecialchars($restaurant['name'] ?? ''); ?></h1>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($restaurant['city'] ?? ''); ?></p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal"><?php echo __('restaurant.add_review'); ?></button>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h2 class="h4 mb-3"><?php echo __('restaurant.menu'); ?></h2>
                <?php foreach (($restaurant['menu'] ?? []) as $category): ?>
                    <div class="mb-4">
                        <h3 class="h5 text-slate-700 mb-2"><?php echo htmlspecialchars($category['name'] ?? ''); ?></h3>
                        <div class="list-group">
                            <?php foreach (($category['items'] ?? []) as $item): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="h6 mb-1 text-slate-800"><?php echo htmlspecialchars($item['name'] ?? ''); ?></h4>
                                        <p class="mb-1 text-muted small"><?php echo htmlspecialchars($item['description'] ?? ''); ?></p>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($item['price'] ?? ''); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h2 class="h5 mb-3"><?php echo __('restaurant.reviews'); ?></h2>
                <div id="reviews" class="space-y-3"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo __('restaurant.leave_review'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="review-form">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <select class="form-select" name="rating">
                            <option value="5">★★★★★</option>
                            <option value="4">★★★★</option>
                            <option value="3">★★★</option>
                            <option value="2">★★</option>
                            <option value="1">★</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo __('restaurant.add_review'); ?></label>
                        <textarea class="form-control" name="comment" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo __('restaurant.submit_review'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function(){
    $('#review-form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '/api/public.php',
            method: 'POST',
            data: $(this).serialize() + '&action=review&slug=<?php echo htmlspecialchars($restaurant['slug'] ?? '', ENT_QUOTES); ?>',
            dataType: 'json',
            success: function(){
                $('#reviewModal').modal('hide');
            }
        });
    });
});
</script>
</body>
</html>
