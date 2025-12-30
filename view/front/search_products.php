<?php
session_start();
include_once '../../Controller/ProductController.php';

$pc = new ProductController();
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$products = $pc->listAllProducts($search, $status);

if (!empty($products)) {
    foreach ($products as $p) {
        $isDisabled = ($p['status'] !== 'available') ? 'disabled' : '';
        $statusText = ($p['status'] === 'available') ? '✅ Ready to rent' : '🔒 Rented Out';
        $btnText = ($p['status'] === 'available') ? 'View Details' : 'Unavailable';
        
        echo "
        <div class='bike-card'>
            <div class='bike-img-container'>
                <span class='bike-tag'>" . htmlspecialchars($p['type']) . "</span>
                <img src='" . htmlspecialchars($p['image_url']) . "' class='bike-img'>
            </div>
            <div class='bike-content'>
                <div class='d-flex justify-content-between align-items-center mb-2'>
                    <h5 class='bike-name mb-0'>" . htmlspecialchars($p['name']) . "</h5>
                    <span class='bike-price'>" . number_format($p['price_per_day'], 2) . " <small>DT</small></span>
                </div>
                <p class='text-muted small'>$statusText</p>
                <a href='product_details.php?id={$p['id']}' class='btn-rent $isDisabled'>
                    $btnText
                </a>
            </div>
        </div>";
    }
} else {
    echo "<div class='text-center w-100 py-5'><p class='text-muted'>No products found matching your search.</p></div>";
}