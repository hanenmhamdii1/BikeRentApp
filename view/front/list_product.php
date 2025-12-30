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
        /* Extra safety to ensure Font Awesome renders correctly */
        .fa-solid, .fas {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
        }
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
                        
                        <?php if($_SESSION['user_role'] == 'owner' && $p['owner_id'] == $_SESSION['user_id']): ?>
                            <div class="owner-actions">
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="action-icon edit" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" 
                                   class="action-icon delete" 
                                   title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this listing?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <img src="<?php echo !empty($p['image_url']) ? htmlspecialchars($p['image_url']) : 'https://via.placeholder.com/400x300?text=No+Image'; ?>" 
                             alt="Vehicle Image" class="bike-img">
                    </div>

                    <div class="bike-content">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="bike-name mb-0"><?php echo htmlspecialchars($p['name']); ?></h5>
                            <span class="bike-price"><?php echo number_format($p['price_per_day'], 2); ?> <small>DT</small></span>
                        </div>
                        <p class="text-muted small mb-3">
                            <?php echo htmlspecialchars(substr($p['description'], 0, 80)) . '...'; ?>
                        </p>
                        <a href="product_details.php?id=<?php echo $p['id']; ?>" class="btn-rent">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-bicycle fa-3x mb-3 text-secondary"></i>
                <p class="text-muted">No vehicles found. Are you an owner? Start by adding one!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>