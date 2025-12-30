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
        /* Force icons to render even if FontAwesome is struggling */
        .fa-solid, .fas { font-family: "Font Awesome 6 Free" !important; font-weight: 900 !important; }
        .owner-actions { display: flex !important; visibility: visible !important; }
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
                        
                        <?php if($_SESSION['user_role'] == 'owner'): ?>
                            <div class="owner-actions">
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="action-icon edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="action-icon delete" onclick="return confirm('Delete?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <img src="<?php echo htmlspecialchars($p['image_url']); ?>" class="bike-img">
                    </div>
                    <div class="bike-content">
                        <h5 class="bike-name"><?php echo htmlspecialchars($p['name']); ?></h5>
                        <p class="bike-price"><?php echo number_format($p['price_per_day'], 2); ?> DT</p>
                        <a href="product_details.php?id=<?php echo $p['id']; ?>" class="btn-rent">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>