<?php
session_start();
include_once '../../Controller/ProductController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list_product.php');
    exit();
}

$pc = new ProductController();
$p = $pc->getProductById($_GET['id']);

if (!$p) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent | <?php echo htmlspecialchars($p['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assests/css/gallery.css">
    
    <style>
        .details-container {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.05);
            margin-top: 20px;
        }
        .details-img { width: 100%; height: 500px; object-fit: cover; }
        .details-content { padding: 40px; }
        .price-badge { font-size: 2.5rem; color: #4a6cf7; font-weight: 800; }
        .info-card { background: #f8fafd; padding: 20px; border-radius: 15px; margin-bottom: 20px; }
        .back-link { text-decoration: none; color: #666; font-weight: 600; display: inline-block; margin-bottom: 20px; transition: 0.3s; }
        .back-link:hover { color: #4a6cf7; }
    </style>
</head>
<body>

<div class="gallery-wrapper">
    <a href="list_product.php" class="back-link">
        <i class="fa fa-arrow-left me-2"></i> Back to Exploration
    </a>

    <div class="details-container">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($p['image_url']); ?>" class="details-img" alt="Vehicle">
            </div>
            
            <div class="col-md-6">
                <div class="details-content">
                    <span class="badge bg-primary mb-2 text-uppercase">
                        <?php echo htmlspecialchars($p['type']); ?>
                    </span>
                    <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($p['name']); ?></h1>
                    
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="price-badge"><?php echo number_format($p['price_per_day'], 2); ?></span>
                        <span class="text-muted ms-2">DT / Day</span>
                    </div>

                    <div class="info-card">
                        <h6 class="text-uppercase fw-bold text-muted small mb-2">Description</h6>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="small text-muted">Availability</div>
                            <?php if($p['status'] == 'available'): ?>
                                <div class="fw-bold text-success"><i class="fa fa-check-circle me-1"></i> Available Now</div>
                            <?php else: ?>
                                <div class="fw-bold text-danger"><i class="fa fa-times-circle me-1"></i> Currently <?php echo ucfirst($p['status']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 text-end">
                            <div class="small text-muted">Vehicle ID</div>
                            <div class="fw-bold">#ME-<?php echo $p['id']; ?></div>
                        </div>
                    </div>

                    <?php if($_SESSION['user_role'] == 'client'): ?>
                        <?php if($p['status'] == 'available'): ?>
                            <a href="book_product.php?id=<?php echo $p['id']; ?>" class="text-decoration-none">
                                <button class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px; font-size: 1.1rem;">
                                    <i class="fa fa-calendar-plus me-2"></i> Book This Vehicle
                                </button>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 py-3 fw-bold disabled" style="border-radius: 12px;">
                                Not Available for Rent
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info border-0" style="border-radius: 12px; background: #eef2ff;">
                            <i class="fa fa-info-circle me-2"></i> You are logged in as an <strong>Owner</strong>. Booking is for Clients only.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>