<?php
session_start(); // Start session at the very top
include_once '../../Controller/AuthController.php';

$auth = new AuthController();
$error = "";

if (isset($_POST['login'])) {
    $user = $auth->login($_POST['email'], $_POST['password']);
    if ($user) {
        // Redirect based on role
        if ($user['role'] == 'admin') {
            header('Location: ../back/admin_dashboard.php');
        } else {
            header('Location: list_product.php');
        }
        exit();
    } else { 
        $error = "Invalid email or password!"; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assests/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side">
                <h3>Welcome Back!</h3>
                <p>Rent your favorite bicycle or scooter in just a few clicks.</p>
            </div>
            <div class="auth-form">
                <h4 class="mb-4">Sign In</h4>
                <?php if($error): ?> 
                    <div class="alert alert-danger" style="font-size: 14px;"><?= $error ?></div> 
                <?php endif; ?>
                
                <form method="POST">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                    
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    
                    <button type="submit" name="login" class="main-btn">Login</button>
                </form>
                <p class="mt-4 text-center">Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </div>
    </div>
</body>
</html>