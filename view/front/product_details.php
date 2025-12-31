<?php
session_start();
include_once '../../Controller/ProductController.php';
include_once '../../Model/Product.php';

if (!isset($_GET['id'])) {
    header('Location: list_product.php');
    exit();
}

$pc = new ProductController();
$product = $pc->getProductById($_GET['id']);

if (!$product) {
    header('Location: list_product.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent | <?= htmlspecialchars($product->getName()) ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/gallery.css"> <style>
        .details-container { margin-top: 50px; margin-bottom: 50px; }
        .product-img { width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .price-tag { font-size: 2rem; color: #4a6cf7; font-weight: 800; }
        .badge-status { padding: 8px 15px; border-radius: 8px; font-weight: 700; }
        .info-card { background: #f8f9fa; border-radius: 15px; padding: 20px; border: none; }
    </style>
</head>
<body class="bg-light">

<div class="container details-container">
    <a href="list_product.php" class="btn btn-link text-decoration-none mb-4" style="color: #4a6cf7;">
        <i class="fa fa-arrow-left me-2"></i> Back to Gallery
    </a>

    <div class="row g-5">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($product->getImage()) ?>" class="product-img" alt="Vehicle Image">
        </div>

        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary px-3 py-2"><?= ucfirst($product->getType()) ?></span>
                <?php 
                    $statusColor = 'success';
                    if($product->getStatus() == 'rented') $statusColor = 'warning';
                    if($product->getStatus() == 'maintenance') $statusColor = 'danger';
                ?>
                <span class="badge-status bg-<?= $statusColor ?> text-white">
                    <?= strtoupper($product->getStatus()) ?>
                </span>
            </div>

            <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($product->getName()) ?></h1>
            
            <div class="mb-4">
                <span class="price-tag"><?= number_format($product->getPrice(), 2) ?> DT</span>
                <span class="text-muted">/ day</span>
            </div>

            <div class="info-card mb-4">
                <h5 class="fw-bold mb-3"><i class="fa fa-info-circle me-2"></i>Description</h5>
                <p class="text-muted mb-0">
                    <?= nl2br(htmlspecialchars($product->getDescription())) ?>
                </p>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <div class="p-3 border rounded text-center">
                        <i class="fa fa-shield-alt text-primary mb-2"></i>
                        <p class="small mb-0">Insured Vehicle</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 border rounded text-center">
                        <i class="fa fa-check-circle text-primary mb-2"></i>
                        <p class="small mb-0">Verified Owner</p>
                    </div>
                </div>
            </div>

            <?php if ($_SESSION['user_role'] == 'client' && $product->getStatus() == 'available'): ?>
                <a href="book_product.php?id=<?= $product->getId() ?>" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 12px; font-size: 1.1rem;">
                    Rent This Vehicle Now
                </a>
            <?php elseif ($_SESSION['user_id'] == $product->getOwnerId()): ?>
                <a href="edit_product.php?id=<?= $product->getId() ?>" class="btn btn-dark w-100 py-3 fw-bold" style="border-radius: 12px;">
                    <i class="fa fa-edit me-2"></i> Edit My Listing
                </a>
            <?php else: ?>
                <button class="btn btn-secondary w-100 py-3 fw-bold disabled" style="border-radius: 12px;">
                    Currently Not Available
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>