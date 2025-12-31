<?php
session_start();
include_once '../../Controller/ProductController.php';
include_once '../../Model/Product.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pc = new ProductController();
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$products = $pc->listAllProducts($search, $status);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent | Explore Vehicles</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/gallery.css">
    
    <style>
        .fa-solid, .fas { font-family: "Font Awesome 6 Free" !important; font-weight: 900 !important; }
        .owner-actions { display: flex !important; visibility: visible !important; }
        
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
            <a href="profile.php" class="me-3 text-decoration-none" style="color: var(--dark); font-weight: 600;">
                <i class="fa-solid fa-user-circle me-1" style="color: #4a6cf7;"></i> My Profile
            </a>

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

    <div class="card border-0 shadow-sm p-4 mb-5" style="border-radius: 20px;">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-bold">Live Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" id="productSearch" class="form-control border-start-0" placeholder="Type name, type or brand...">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Availability</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Vehicles</option>
                    <option value="available">Available Only</option>
                    <option value="rented">Rented Out</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bike-grid" id="productGrid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <div class="bike-card">
                    <div class="bike-img-container">
                        <span class="bike-tag"><?php echo htmlspecialchars($p->getType()); ?></span>
                        
                        <?php 
                            $statusClass = 'status-available';
                            if ($p->getStatus() == 'rented') $statusClass = 'status-rented';
                            if ($p->getStatus() == 'maintenance') $statusClass = 'status-maintenance';
                        ?>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($p->getStatus()); ?>
                        </span>
                        
                        <?php if($_SESSION['user_role'] == 'owner'): ?>
                            <div class="owner-actions">
                                <a href="edit_product.php?id=<?php echo $p->getId(); ?>" class="action-icon edit" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="delete_product.php?id=<?php echo $p->getId(); ?>" class="action-icon delete" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <img src="<?php echo htmlspecialchars($p->getImage()); ?>" class="bike-img" alt="Vehicle">
                    </div>

                    <div class="bike-content">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="bike-name mb-0"><?php echo htmlspecialchars($p->getName()); ?></h5>
                            <p class="bike-price mb-0"><?php echo number_format($p->getPrice(), 2); ?> <small>DT</small></p>
                        </div>
                        
                        <p class="text-muted small mb-3">
                            <?php echo htmlspecialchars(substr($p->getDescription(), 0, 70)) . '...'; ?>
                        </p>

                        <?php if($p->getStatus() == 'available'): ?>
                            <a href="product_details.php?id=<?php echo $p->getId(); ?>" class="btn-rent">View Details</a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 py-2 disabled" style="border-radius: 10px; font-weight: 600; background: #bdc3c7; border: none; cursor: not-allowed;">
                                <i class="fa fa-lock me-2"></i> Currently <?php echo ucfirst($p->getStatus()); ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('productSearch');
    const statusSelect = document.getElementById('statusFilter');
    const grid = document.getElementById('productGrid');

    function updateGallery() {
        const s = searchInput.value;
        const st = statusSelect.value;
        fetch(`search_products.php?search=${encodeURIComponent(s)}&status=${st}`)
            .then(response => response.text())
            .then(data => { grid.innerHTML = data; })
            .catch(err => console.error('Error updating gallery:', err));
    }

    searchInput.addEventListener('input', updateGallery);
    statusSelect.addEventListener('change', updateGallery);
});
</script>
</body>
</html> 