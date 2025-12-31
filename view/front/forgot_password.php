<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"> </head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side">
                <h3>Password Recovery</h3>
                <p>Don't worry! Just enter your email and we will send you a secure 6-digit code to reset your account.</p>
            </div>
            <div class="auth-form">
                <h4 class="mb-3">Forgot Password</h4>
                <p class="text-muted small mb-4">A reset code will be sent to your inbox.</p>
                
                <form action="otp_handler.php" method="POST">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                    
                    <button type="submit" name="send_otp" class="main-btn">Send Reset Code</button>
                </form>
                <p class="mt-4 text-center small">Remembered it? <a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>