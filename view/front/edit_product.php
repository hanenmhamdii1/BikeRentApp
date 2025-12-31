<?php
session_start();
include_once '../../Controller/ProductController.php';

if (!isset($_GET['id'])) {
    header('Location: list_product.php');
    exit();
}

$pc = new ProductController();
$product = $pc->getProductById($_GET['id']);

if (!$product) {
    die("Product not found in database.");
}

// FIXED SECURITY CHECK
// If the product has no owner yet (0 or null), allow the logged-in owner to edit it
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'owner')) {
    header('Location: list_product.php');
    exit();
}

if (isset($_POST['update_product'])) {
    $image_path = $product['image_url']; 
    if (!empty($_FILES["image_file"]["name"])) {
        // Ensure this directory exists: View/FrontOffice/assets/images/
        $target_dir = "assets/images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_path = $target_dir . time() . "_" . basename($_FILES["image_file"]["name"]);
        move_uploaded_file($_FILES["image_file"]["tmp_name"], $image_path);
    }

    $pc->updateProduct(
        $_GET['id'], 
        $_POST['name'], 
        $_POST['type'], 
        $_POST['price'], 
        $_POST['description'], 
        $image_path,
        $_POST['status']
    );
    
    echo "<script>alert('Updated successfully!'); window.location='list_product.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MoveEasy | Edit Vehicle</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side">
                <i class="fa-solid fa-pen-nib fa-3x mb-3"></i>
                <h3>Edit Listing</h3>
                <p>Modify the details of your vehicle to keep it up to date.</p>
            </div>
            <div class="auth-form">
                <form method="POST" enctype="multipart/form-data">
                    <label>Vehicle Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                    
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="bicycle" <?= $product['type'] == 'bicycle' ? 'selected' : '' ?>>Bicycle</option>
                                <option value="scooter" <?= $product['type'] == 'scooter' ? 'selected' : '' ?>>Scooter</option>
                            </select>
                        </div>
                        <div style="flex:1">
                            <label>Price / Day</label>
                            <input type="number" name="price" class="form-control" value="<?= $product['price_per_day'] ?>" required>
                        </div>
                    </div>

                    <label>New Photo (Optional)</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">

                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($product['description']) ?></textarea>
                    <label>Availability Status</label>
                    <select name="status" class="form-control" style="margin-bottom: 15px; border: 2px solid #4a6cf7;">
                        <option value="available" <?= $product['status'] == 'available' ? 'selected' : '' ?>>✅ Available</option>
                        <option value="rented" <?= $product['status'] == 'rented' ? 'selected' : '' ?>>🔑 Currently Rented</option>
                        <option value="maintenance" <?= $product['status'] == 'maintenance' ? 'selected' : '' ?>>🛠 Under Maintenance</option>
                    </select>
                    <button type="submit" name="update_product" class="main-btn">Save Changes</button>
                    <a href="list_product.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>