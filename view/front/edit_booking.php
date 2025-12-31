<?php
session_start();
include_once '../../Controller/RentController.php';
include_once '../../Controller/ProductController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header('Location: login.php');
    exit();
}

$rc = new RentController();
$pc = new ProductController();

$db = Database::connect();
$stmt = $db->prepare("SELECT r.*, p.price_per_day, p.name FROM rentals r JOIN products p ON r.product_id = p.id WHERE r.id = ?");
$stmt->execute([$_GET['id']]);
$rental = $stmt->fetch();

if (!$rental || $rental['user_id'] != $_SESSION['user_id']) {
    die("Unauthorized access.");
}

if (isset($_POST['update_booking'])) {
    $start = new DateTime($_POST['start_date']);
    $end = new DateTime($_POST['end_date']);
    $interval = $start->diff($end);
    $days = $interval->days;
    
    if ($days <= 0) $days = 1;
    
    $newTotal = $days * $rental['price_per_day'];

   if ($rc->updateRentalDates($rental['id'], $_POST['start_date'], $_POST['end_date'], $newTotal)) {
        header("Location: booking_success.php?id=" . $rental['id']);
        exit();
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Edit Booking</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side">
                <i class="fa-solid fa-calendar-check fa-3x mb-3"></i>
                <h3>Modify Dates</h3>
                <p>Updating booking for: <br><strong><?php echo htmlspecialchars($rental['name']); ?></strong></p>
                <p>Daily Rate: <strong><?php echo $rental['price_per_day']; ?> DT</strong></p>
            </div>
            
            <div class="auth-form">
                <form method="POST">
                    <h4 class="mb-4 fw-bold" style="color:#2e2e2e;">New Rental Period</h4>
                    
                    <label>New Pickup Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" 
                           value="<?php echo $rental['start_date']; ?>" required min="<?php echo date('Y-m-d'); ?>">

                    <label>New Return Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" 
                           value="<?php echo $rental['end_date']; ?>" required min="<?php echo date('Y-m-d'); ?>">

                    <div id="priceSummary" style="background: #f0f7ff; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 2px dashed #4a6cf7;">
                        <span class="text-muted">New Calculated Total:</span>
                        <h4 class="fw-bold mb-0" id="totalDisplay" style="color:#4a6cf7;"><?php echo $rental['total_price']; ?> DT</h4>
                    </div>

                    <button type="submit" name="update_booking" class="main-btn">
                        Update My Booking
                    </button>
                    <a href="my_rentals.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Go Back</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        const start = document.getElementById('start_date');
        const end = document.getElementById('end_date');
        const totalDisp = document.getElementById('totalDisplay');
        // FIX: Changed $rental['price'] to $rental['price_per_day']
        const pricePerDay = <?php echo $rental['price_per_day']; ?>;

        function updatePrice() {
            if(start.value && end.value) {
                const s = new Date(start.value);
                const e = new Date(end.value);
                const diff = Math.ceil((e - s) / (1000 * 60 * 60 * 24));
                if(diff > 0) {
                    totalDisp.innerText = (diff * pricePerDay).toFixed(2) + " DT";
                } else {
                    totalDisp.innerText = "0.00 DT";
                }
            }
        }
        start.addEventListener('change', updatePrice);
        end.addEventListener('change', updatePrice);
    </script>
</body>
</html>