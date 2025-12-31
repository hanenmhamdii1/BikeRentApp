<?php
session_start();
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | New Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side">
                <h3>Secure Account</h3>
                <p>Create a strong password to keep your BikeRent account safe.</p>
            </div>
            <div class="auth-form">
                <h4 class="mb-3">Set New Password</h4>
                <p class="text-muted small mb-4">Resetting password for: <b><?php echo htmlspecialchars($email); ?></b></p>

                <form action="otp_handler.php" method="POST">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    
                    <label>New Password</label>
                    <input type="password" name="new_pass" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
                    
                    <label>Confirm New Password</label>
                    <input type="password" class="form-control" placeholder="Repeat password" required>
                    
                    <button type="submit" name="update_password" class="main-btn">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>