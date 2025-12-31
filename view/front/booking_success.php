<?php
session_start();
include_once '../../Controller/RentController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$rental_id = $_GET['id'] ?? null;
if (!$rental_id) {
    header('Location: my_rentals.php');
    exit();
}

// Fetch the updated details for the receipt
$db = Database::connect();
$stmt = $db->prepare("SELECT r.*, p.name as bike_name, p.type FROM rentals r JOIN products p ON r.product_id = p.id WHERE r.id = ?");
$stmt->execute([$rental_id]);
$rental = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Update Successful</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .receipt-card { background: white; border-radius: 20px; padding: 40px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .check-icon { width: 80px; height: 80px; background: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; }
        .receipt-details { text-align: left; background: #f8f9fa; border-radius: 12px; padding: 20px; margin: 20px 0; border: 1px solid #eee; }
        .receipt-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 5px; }
    </style>
</head>
<body style="background: #f4f7fe; display: flex; align-items: center; min-height: 100vh;">

    <div class="container">
        <div class="receipt-card">
            <div class="check-icon">
                <i class="fa fa-check"></i>
            </div>
            <h2 class="fw-bold">Booking Updated!</h2>
            <p class="text-muted">Your rental dates have been successfully modified.</p>

            <div class="receipt-details">
                <div class="receipt-row">
                    <span>Order ID:</span>
                    <strong>#BR-<?php echo $rental['id']; ?></strong>
                </div>
                <div class="receipt-row">
                    <span>Vehicle:</span>
                    <strong><?php echo htmlspecialchars($rental['bike_name']); ?></strong>
                </div>
                <div class="receipt-row">
                    <span>New Pickup:</span>
                    <strong><?php echo $rental['start_date']; ?></strong>
                </div>
                <div class="receipt-row">
                    <span>New Return:</span>
                    <strong><?php echo $rental['end_date']; ?></strong>
                </div>
                <div class="receipt-row" style="border: none; margin-top: 10px;">
                    <span style="font-size: 1.1rem;">Total Amount:</span>
                    <strong style="font-size: 1.1rem; color: #4a6cf7;"><?php echo number_format($rental['total_price'], 2); ?> DT</strong>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="my_rentals.php" class="main-btn" style="text-decoration: none;">View My Rentals</a>
                <a href="list_product.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">Back to Gallery</a>
            </div>
        </div>
    </div>

</body>
</html>