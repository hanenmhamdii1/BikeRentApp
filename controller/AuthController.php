<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../config.php');

class AuthController {
    
    public function register($name, $email, $password, $role = 'client') {
        $db = Database::connect();
        $hashPassword = password_hash($password, PASSWORD_BCRYPT); 
        
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashPassword,
                'role' => $role
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function login($email, $password) {
        $db = Database::connect();
        $sql = "SELECT * FROM users WHERE email = :email";
        
        try {
            $query = $db->prepare($sql);
            $query->execute(['email' => $email]);
            $user = $query->fetch();

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'approved' && $user['role'] !== 'admin') {
                    echo "<script>alert('Your account is awaiting admin approval.'); window.location='login.php';</script>";
                    exit();
                }

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];
                
                return $user; 
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return false;
    }
   public function sendOTP($email) {
        $db = Database::connect();
        $otp = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        $stmt = $db->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
        $stmt->execute([$otp, $expires, $email]);

        if ($stmt->rowCount() > 0) {
            return $this->mailOTP($email, $otp);
        }
        return false;
    }

   private function mailOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Port       = 2525; 
        $mail->Username   = '9dfadfb3b4ce19'; 
        $mail->Password   = '4cc85f578c5030'; 
        
        $mail->setFrom('test@bikerent.com', 'BikeRent Test');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Reset Code';
        $mail->Body    = "Hello, your OTP is: <b>$otp</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log($mail->ErrorInfo);
        return false;
    }
}

    public function verifyOTP($email, $otp) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND otp_code = ? AND otp_expires_at > NOW()");
        $stmt->execute([$email, $otp]);
        return $stmt->fetch();
    }


public function resetPassword($email, $newPassword) {
    $db = Database::connect();
    
    $hashed = password_hash($newPassword, PASSWORD_BCRYPT); 
    
    $sql = "UPDATE users SET 
            password = :password, 
            otp_code = NULL, 
            otp_expires_at = NULL 
            WHERE email = :email";
            
    try {
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            'password' => $hashed,
            'email' => $email
        ]);
        
        return $stmt->rowCount() > 0; 
    } catch (Exception $e) {
        return false;
    }
}

    

}
?>