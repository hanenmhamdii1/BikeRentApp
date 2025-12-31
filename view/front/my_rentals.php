<?php
session_start();
include_once '../../Controller/RentController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header('Location: login.php');
    exit();
}

$rc = new RentController();

if (isset($_GET['cancel_id'])) {
    $rc->cancelRental($_GET['cancel_id']);
    echo "<script>alert('Rental Cancelled Successfully'); window.location='my_rentals.php';</script>";
    exit();
}

$myRentals = $rc->getRentalsByClient($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent | My Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/gallery.css">
</head>
<body>

<div class="gallery-wrapper">
    <nav class="top-nav">
        <div class="logo">
            <h3 style="color: #4a6cf7; font-weight: 800; margin: 0;">BikeRent</h3>
        </div>
        <div class="user-badge">
            <a href="list_product.php" style="text-decoration:none; color:#4a6cf7; font-weight:600; margin-right:20px;">
                <i class="fa fa-search me-1"></i> Explore More
            </a>
            <span>Hello, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </nav>

    <div class="mb-5">
        <h2 class="fw-bold">My Rental History</h2>
        <p class="text-muted">Manage your active bookings and view your past trips.</p>
    </div>

    <div class="row">
        <?php if (!empty($myRentals)): ?>
            <?php foreach ($myRentals as $r): ?>
                <div class="col-md-12 mb-4">
                    <div class="card border-0 shadow-sm p-3" style="border-radius: 20px;">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="<?php echo htmlspecialchars($r['image_url']); ?>" 
                                     style="width: 100%; height: 100px; object-fit: cover; border-radius: 15px;">
                            </div>
                            
                            <div class="col-md-3">
                                <span class="badge bg-light text-primary text-uppercase mb-1" style="font-size: 10px;">
                                    <?php echo htmlspecialchars($r['type']); ?>
                                </span>
                                <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($r['bike_name']); ?></h5>
                                <small class="text-muted">Order ID: #BR-<?php echo $r['id']; ?></small>
                            </div>

                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-calendar-alt text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Duration</small>
                                        <strong><?php echo $r['start_date']; ?></strong> to <strong><?php echo $r['end_date']; ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 text-center">
                                <small class="text-muted d-block">Total Paid</small>
                                <span class="fw-bold text-primary" style="font-size: 1.2rem;">
                                    <?php echo number_format($r['total_price'], 2); ?> DT
                                </span>
                            </div>

                            <div class="col-md-2 text-end">
                                <?php if($r['status'] == 'active'): ?>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="edit_booking.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-primary" style="border-radius: 8px;">
                                            <i class="fa fa-edit me-1"></i> Update
                                        </a>
                                        <a href="my_rentals.php?cancel_id=<?php echo $r['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           style="border-radius: 8px;"
                                           onclick="return confirm('Are you sure you want to cancel this rental?')">
                                            <i class="fa fa-trash me-1"></i> Cancel
                                        </a>
                                    </div>
                                <?php elseif($r['status'] == 'cancelled'): ?>
                                    <span class="badge bg-danger p-2 px-3" style="border-radius: 8px;">Cancelled</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary p-2 px-3" style="border-radius: 8px;">Completed</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                <h4>No rentals yet</h4>
                <p class="text-muted">You haven't rented any vehicles yet. Start exploring!</p>
                <a href="list_product.php" class="btn btn-primary px-4">Browse Bikes</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>