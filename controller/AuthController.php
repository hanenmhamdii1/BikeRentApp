<?php
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
                // 1. Check Approval Status
                if ($user['status'] !== 'approved' && $user['role'] !== 'admin') {
                    echo "<script>alert('Your account is awaiting admin approval.'); window.location='login.php';</script>";
                    exit();
                }

                // 2. Setup Session
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];
                
                return $user; // Return user data to the view for redirection
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return false;
    }
}
?>