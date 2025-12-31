<?php
session_start();
include_once '../../Controller/ProductController.php';
include_once '../../Controller/RentController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list_product.php');
    exit();
}

$pc = new ProductController();
$product = $pc->getProductById($_GET['id']);

if (!$product || $product['status'] !== 'available') {
    header('Location: list_product.php');
    exit();
}

if (isset($_POST['confirm_booking'])) {
    $rc = new RentController();
    
    $start = new DateTime($_POST['start_date']);
    $end = new DateTime($_POST['end_date']);
    $interval = $start->diff($end);
    $days = $interval->days;
    
    if ($days <= 0) $days = 1; // Minimum 1 day charge
    
    $total = $days * $product['price_per_day'];
    
    if ($rc->createRent($_SESSION['user_id'], $product['id'], $_POST['start_date'], $_POST['end_date'], $total)) {
        echo "<script>alert('Booking Successful! Total: " . $total . " DT'); window.location='my_rentals.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Confirm Booking</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .summary-box { background: #f0f7ff; border: 2px dashed #4a6cf7; border-radius: 12px; padding: 15px; margin-bottom: 20px; display: none; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side">
                <i class="fa-solid fa-receipt fa-3x mb-3"></i>
                <h3>Booking Summary</h3>
                <p>Vehicle: <strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
                <p>Rate: <strong><?php echo $product['price_per_day']; ?> DT / Day</strong></p>
            </div>
            
            <div class="auth-form">
                <form method="POST" id="rentForm">
                    <h4 class="mb-4 fw-bold" style="color:#2e2e2e;">Rental Dates</h4>
                    
                    <label>Pickup Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">

                    <label>Return Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">

                    <div id="priceSummary" class="summary-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total for <span id="dayCount">0</span> days:</span>
                            <span class="fw-bold" id="totalDisplay" style="color:#4a6cf7; font-size:1.3rem;">0.00 DT</span>
                        </div>
                    </div>

                    <button type="submit" name="confirm_booking" class="main-btn">
                        <i class="fa fa-check-circle me-2"></i> Confirm Rental
                    </button>
                    <a href="product_details.php?id=<?php echo $product['id']; ?>" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        const start = document.getElementById('start_date');
        const end = document.getElementById('end_date');
        const summary = document.getElementById('priceSummary');
        const dayCount = document.getElementById('dayCount');
        const totalDisp = document.getElementById('totalDisplay');
        const price = <?php echo $product['price_per_day']; ?>;

        function updatePrice() {
            if(start.value && end.value) {
                const sDate = new Date(start.value);
                const eDate = new Date(end.value);
                const diff = Math.ceil((eDate - sDate) / (1000 * 60 * 60 * 24));
                
                if(diff > 0) {
                    summary.style.display = 'block';
                    dayCount.innerText = diff;
                    totalDisp.innerText = (diff * price).toFixed(2) + " DT";
                } else {
                    summary.style.display = 'none';
                }
            }
        }
        start.addEventListener('change', updatePrice);
        end.addEventListener('change', updatePrice);
    </script>
</body>
</html>