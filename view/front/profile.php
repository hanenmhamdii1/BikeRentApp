<?php
session_start();
include_once '../../Controller/AuthController.php';
$auth = new AuthController();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

    $user = $auth->getUserById($_SESSION['user_id']);

$message = "";
if (isset($_POST['update_profile'])) {
    if ($auth->updateProfile($_SESSION['user_id'], $_POST['name'], $_POST['email'])) {
        $message = "<div class='alert alert-success'>Profile updated!</div>";
        $user = $auth->getUserById($_SESSION['user_id']);
        $_SESSION['user_name'] = $user->getName(); 
    } else {
        $message = "<div class='alert alert-danger'>Update failed.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side" style="background: var(--dark);">
                <h3>My Profile</h3>
                <p>Keep your contact information up to date so we can reach you about your rentals.</p>
                <a href="list_product.php" class="text-white small mt-auto">← Back to Gallery</a>
            </div>
            <div class="auth-form">
                <h4 class="mb-4">Edit Information</h4>
                <?php echo $message; ?>

                <form method="POST">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= htmlspecialchars($user->getName()) ?>" required>
                    
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                    
                    <label>Account Role</label>
                    <input type="text" class="form-control bg-light" 
                           value="<?= ucfirst($user->getRole()) ?>" disabled>
                    
                    <button type="submit" name="update_profile" class="main-btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>