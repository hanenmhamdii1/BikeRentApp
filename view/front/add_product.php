<?php
session_start();
include_once '../../Controller/ProductController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'owner') {
    header('Location: login.php');
    exit();
}

$pc = new ProductController();

if (isset($_POST['add_product'])) {
    $target_dir = "assets/images/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $file_name = time() . "_" . basename($_FILES["image_file"]["name"]);
    $target_file = $target_dir . $file_name;

    // Use tmp_name (fixed typo from previous suggestion)
    if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
        $pc->addProduct(
            $_POST['name'], 
            $_POST['type'], 
            $_POST['price'], 
            $_POST['description'], 
            $target_file, 
            $_SESSION['user_id']
        );
        echo "<script>alert('Product added successfully!'); window.location='list_product.php';</script>";
    } else {
        echo "<script>alert('Error uploading image. Make sure assets/images/ folder exists.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent | List Your Vehicle</title>
    <link rel="stylesheet" href="assests/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
</head>
<body>
    <div class="auth-container">
        <a href="list_product.php" style="position: absolute; top: 30px; left: 30px; text-decoration: none; color: #4a6cf7; font-weight: 600; z-index: 10;">
            <i class="fa fa-arrow-left"></i> Back to Gallery
        </a>

        <div class="auth-card">
            <div class="auth-side">
                <div class="mb-4">
                    <i class="fa fa-bicycle fa-4x"></i>
                </div>
                <h3>Start Earning!</h3>
                <p>List your bike or scooter and reach hundreds of riders in your area.</p>
            </div>
            
            <div class="auth-form">
                <h4 class="mb-4" style="font-weight: 800; color: #2e2e2e;">Vehicle Details</h4>
                
                <form method="POST" enctype="multipart/form-data">
                    <label>Vehicle Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Rockrider ST100" required>

                    <div style="display: flex; gap: 15px;">
                        <div style="flex: 1;">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="bicycle">Bicycle</option>
                                <option value="scooter">Electric Scooter</option>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label>Price / Day (DT)</label>
                            <input type="number" name="price" class="form-control" placeholder="20" required>
                        </div>
                    </div>

                    <label>Upload Photo</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*" required>

                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Tell us about the condition..."></textarea>

                    <button type="submit" name="add_product" class="main-btn">
                        <i class="fa fa-paper-plane me-2"></i> Publish Listing
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>