<?php
session_start();
include_once '../../Controller/AuthController.php';

$auth = new AuthController();


if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    
    if ($auth->sendOTP($email)) {
        header("Location: verify_otp.php?email=" . urlencode($email));
        exit(); 
    } else {
        header("Location: forgot_password.php?error=mail_failed");
        exit();
    }
}
if (isset($_POST['verify_code'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    if ($auth->verifyOTP($email, $otp)) {
        header("Location: reset_password.php?email=" . urlencode($email));
        exit();
    } else {
        header("Location: verify_otp.php?email=" . urlencode($email) . "&error=invalid_otp");
        exit();
    }
}

// otp_handler.php

if (isset($_POST['update_password'])) {
    $email = $_POST['email'];
    $new_pass = $_POST['new_pass'];

    if ($auth->resetPassword($email, $new_pass)) {
        // SUCCESS: Go to login with a success message
        header("Location: login.php?reset=success");
        exit();
    } else {
        // FAIL: Go back to the reset page
        header("Location: reset_password.php?email=" . urlencode($email) . "&error=update_failed");
        exit();
    }
}
?>