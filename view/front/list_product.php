<?php
session_start();
include_once '../../Controller/ProductController.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pc = new ProductController();
$products = $pc->listAllProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent | Explore Vehicles</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assests/css/gallery.css">
    
    <style>
        /* Force icons and visibility for high-priority elements */
        .fa-solid, .fas { font-family: "Font Awesome 6 Free" !important; font-weight: 900 !important; }
        .owner-actions { display: flex !important; visibility: visible !important; }
        
        /* Status Badge Styles */
        .status-badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            z-index: 10;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-available { background: #28a745; color: white; }
        .status-rented { background: #ffc107; color: #000; }
        .status-maintenance { background: #dc3545; color: white; }
    </style>
</head>
<body>

<div class="gallery-wrapper">
    <nav class="top-nav">
        <div class="logo">
            <h3 style="color: #4a6cf7; font-weight: 800; margin: 0;">BikeRent</h3>
        </div>
        <div class="user-badge">
            <span>Hello, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
            
            <?php if($_SESSION['user_role'] == 'owner'): ?>
                <a href="add_product.php" class="add-btn">
                    <i class="fa-solid fa-plus"></i> Add Vehicle
                </a>
            <?php endif; ?>
            <?php if($_SESSION['user_role'] == 'client'): ?>
        <a href="my_rentals.php" class="add-btn" style="background: #2e2e2e;">
            <i class="fa-solid fa-clock-rotate-left"></i> My Rentals
        </a>
    <?php endif; ?>
            <a href="logout.php" class="logout-link">
                <i class="fa-solid fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="mb-5">
        <h2 class="fw-bold">Available for Rent</h2>
        <p class="text-muted">Choose from our selection of premium bicycles and scooters.</p>
    </div>

    <div class="bike-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <div class="bike-card">
                    <div class="bike-img-container">
                        <span class="bike-tag"><?php echo htmlspecialchars($p['type']); ?></span>
                        
                        <?php 
                            $statusClass = 'status-available';
                            if ($p['status'] == 'rented') $statusClass = 'status-rented';
                            if ($p['status'] == 'maintenance') $statusClass = 'status-maintenance';
                        ?>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($p['status'] ?? 'available'); ?>
                        </span>
                        
                        <?php if($_SESSION['user_role'] == 'owner'): ?>
                            <div class="owner-actions">
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="action-icon edit" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="action-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this listing?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <img src="<?php echo htmlspecialchars($p['image_url']); ?>" class="bike-img" alt="Vehicle">
                    </div>

                    <div class="bike-content">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="bike-name mb-0"><?php echo htmlspecialchars($p['name']); ?></h5>
                            <p class="bike-price mb-0"><?php echo number_format($p['price_per_day'], 2); ?> <small>DT</small></p>
                        </div>
                        
                        <p class="text-muted small mb-3">
                            <?php echo htmlspecialchars(substr($p['description'], 0, 70)) . '...'; ?>
                        </p>

                        <?php if($p['status'] == 'available'): ?>
                            <a href="product_details.php?id=<?php echo $p['id']; ?>" class="btn-rent">
                                View Details
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 disabled" style="border-radius: 10px; padding: 12px; font-weight: 600;">
                                Currently Unavailable
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 w-100">
                <i class="fa-solid fa-bicycle fa-3x text-muted mb-3"></i>
                <p class="text-muted">No vehicles found in the gallery.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>