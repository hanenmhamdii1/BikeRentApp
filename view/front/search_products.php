<?php
session_start();
include_once '../../Controller/ProductController.php';
include_once '../../Model/Product.php'; 

$pc = new ProductController();

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$products = $pc->listAllProducts($search, $status);

if (!empty($products)): 
    foreach ($products as $p): 
        $statusClass = 'status-available';
        if ($p->getStatus() == 'rented') $statusClass = 'status-rented';
        if ($p->getStatus() == 'maintenance') $statusClass = 'status-maintenance';
    ?>
        <div class="bike-card">
            <div class="bike-img-container">
                <span class="bike-tag"><?php echo htmlspecialchars($p->getType()); ?></span>
                
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
    <?php endforeach; 
else: ?>
    <div class="text-center py-5 w-100">
        <i class="fa-solid fa-bicycle fa-3x text-muted mb-3"></i>
        <p class="text-muted">No vehicles found matching your search.</p>
    </div>
<?php endif; ?>