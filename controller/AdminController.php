<?php
include_once(__DIR__ . '/../config.php');

class AdminController {
    
    public function listAllUsers() {
        $db = Database::connect();
        $query = $db->query("SELECT id, name, email, role, status, created_at FROM users WHERE role != 'admin' ORDER BY created_at DESC");
        return $query->fetchAll();
    }

    public function updateUserStatus($userId, $status) {
        $db = Database::connect();
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $query = $db->prepare($sql);
        return $query->execute(['status' => $status, 'id' => $userId]);
    }

    public function getStats() {
        $db = Database::connect();
        $stats = [];
        $stats['total_users'] = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['total_bikes'] = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $stats['active_rents'] = $db->query("SELECT COUNT(*) FROM rentals WHERE status = 'active'")->fetchColumn();
        return $stats;
    }
}
?>