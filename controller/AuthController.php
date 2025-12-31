<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

include_once(__DIR__ . '/../model/User.php');

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
        $row = $query->fetch(); // This is the raw array

        if ($row && password_verify($password, $row['password'])) {
            // Create the User object using the Constructor
            $userObj = new User(
                $row['id'], 
                $row['name'], 
                $row['email'], 
                $row['password'], 
                $row['role']
            );

            // Example of using a Setter if you needed to modify something 
            // before the session starts (e.g., sanitizing a name)
            // $userObj->setName(ucfirst($userObj->getName()));

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_id'] = $userObj->getId();
            $_SESSION['user_role'] = $userObj->getRole();
            $_SESSION['user_name'] = $userObj->getName();
            
            return $userObj; // Now returning an OBJECT, not an array
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
    return false;
}


public function updateProfile($id, $name, $email) {
    $db = Database::connect();
    
    $user = new User($id, null, null, null, null);
    $user->setName($name);
    $user->setEmail($email);

    try {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $query = $db->prepare($sql);
        
        return $query->execute([
            'name'  => $user->getName(),
            'email' => $user->getEmail(),
            'id'    => $user->getId()
        ]);
    } catch (Exception $e) {
        return false;
    }
}

public function getUserById($id) {
    $db = Database::connect();
    $query = $db->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$id]);
    $row = $query->fetch();

    if ($row) {
        return new User($row['id'], $row['name'], $row['email'], $row['password'], $row['role']);
    }
    return null;
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
        // --- REAL SMTP SETTINGS ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';             // Gmail SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hanenmhamdii1@gmail.com';      // Your real Gmail address
        $mail->Password   = 'xpzo febd jrsf gzss';       // The 16-digit App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS for security
        $mail->Port       = 587;                          // Standard TLS port
        
        // --- EMAIL CONTENT ---
        $mail->setFrom('hanenmhamdii1@gmail.com', 'BikeRent');
        $mail->addAddress($email);                   

        $mail->isHTML(true);
        $mail->Subject = 'Your Password Reset OTP';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                <h2 style='color: #4a6cf7;'>BikeRent Password Reset</h2>
                <p>Hello,</p>
                <p>You requested a password reset. Use the code below to proceed:</p>
                <div style='font-size: 24px; font-weight: bold; color: #333; letter-spacing: 5px; margin: 20px 0;'>$otp</div>
                <p style='color: #777; font-size: 12px;'>This code will expire in 15 minutes.</p>
            </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // For debugging real mail issues, uncomment the line below:
        // echo "Mailer Error: " . $mail->ErrorInfo;
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