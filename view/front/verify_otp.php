<?php
session_start();
$email = $_GET['email'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Verify Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .otp-input {
            letter-spacing: 8px;
            font-size: 24px !important;
            font-weight: 700 !important;
            text-align: center;
            background: #f0f4ff !important;
            border: 2px solid #4a6cf7 !important;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-side" style="background: #28a745;"> <h3>Verification</h3>
                <p>We've sent a 6-digit code to <b><?php echo htmlspecialchars($email); ?></b>. Please enter it to continue.</p>
            </div>
            <div class="auth-form">
                <h4 class="mb-3">Verify OTP</h4>
                
                <?php if($error == 'invalid_otp'): ?>
                    <div class="alert alert-danger py-2" style="font-size: 13px;">Invalid or expired code. Please try again.</div>
                <?php endif; ?>

                <form action="otp_handler.php" method="POST">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    
                    <label>Enter 6-Digit Code</label>
                    <input type="text" name="otp" class="form-control otp-input" placeholder="000000" maxlength="6" required autofocus>
                    
                    <button type="submit" name="verify_code" class="main-btn" style="background: #28a745;">Verify & Continue</button>
                </form>
                <p class="mt-4 text-center small">Didn't get the code? <a href="forgot_password.php">Resend</a></p>
            </div>
        </div>
    </div>
</body>
</html>