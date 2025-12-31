<?php
session_start();
include_once '../../Controller/ProductController.php';
include_once '../../Model/Product.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'owner') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list_product.php');
    exit();
}

$pc = new ProductController();
$product = $pc->getProductById($_GET['id']);

if (!$product) {
    die("Product not found.");
}

if (isset($_POST['update_product'])) {
    $product->setName($_POST['name']);
    $product->setType($_POST['type']);
    $product->setPrice((float)$_POST['price']);
    $product->setDescription($_POST['description']);
    $product->setStatus($_POST['status']);

    if (!empty($_FILES["image_file"]["name"])) {
        $target_dir = "assets/images/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $image_path = $target_dir . time() . "_" . basename($_FILES["image_file"]["name"]);
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $image_path)) {
            $product->setImage($image_path); 
        }
    }

    if ($pc->updateProduct($product)) {
        echo "<script>alert('Updated successfully!'); window.location='list_product.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Edit Vehicle</title>
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
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product->getName()) ?>" required>
                    
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="bicycle" <?= $product->getType() == 'bicycle' ? 'selected' : '' ?>>Bicycle</option>
                                <option value="scooter" <?= $product->getType() == 'scooter' ? 'selected' : '' ?>>Scooter</option>
                            </select>
                        </div>
                        <div style="flex:1">
                            <label>Price / Day</label>
                            <input type="number" name="price" class="form-control" value="<?= $product->getPrice() ?>" required>
                        </div>
                    </div>

                    <label>New Photo (Optional)</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">

                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($product->getDescription()) ?></textarea>
                    
                    <label>Availability Status</label>
                    <select name="status" class="form-control" style="margin-bottom: 15px; border: 2px solid #4a6cf7;">
                        <option value="available" <?= $product->getStatus() == 'available' ? 'selected' : '' ?>>✅ Available</option>
                        <option value="rented" <?= $product->getStatus() == 'rented' ? 'selected' : '' ?>>🔑 Currently Rented</option>
                        <option value="maintenance" <?= $product->getStatus() == 'maintenance' ? 'selected' : '' ?>>🛠 Under Maintenance</option>
                    </select>

                    <button type="submit" name="update_product" class="main-btn">Save Changes</button>
                    <a href="list_product.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>