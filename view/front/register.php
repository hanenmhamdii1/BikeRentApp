<?php
include_once '../../Controller/AuthController.php';
$auth = new AuthController();
if (isset($_POST['register'])) {
    if($auth->register($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
        header('Location: login.php?success=1');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assests/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side">
                <h3>Join Us!</h3>
                <p>Start your journey by creating a free account today.</p>
            </div>
            <div class="auth-form">
                <h4 class="mb-4">Create Account</h4>
                <form method="POST">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                    
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    
                    <label>Register as</label>
                    <select name="role" class="form-control">
                        <option value="client">Client (I want to rent)</option>
                        <option value="owner">Owner (I want to provide bikes)</option>
                    </select>

                    <button type="submit" name="register" class="main-btn">Sign Up</button>
                </form>
                <p class="mt-4 text-center">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>