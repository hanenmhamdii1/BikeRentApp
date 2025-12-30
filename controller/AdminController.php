<?php
include_once(__DIR__ . '/../config.php');

class AdminController {
    
   

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
            // Inside AdminController class
            // 1. Sort Users by Name
        public function listAllUsers() {
            $db = Database::connect();
            // Added ORDER BY name ASC
            $query = $db->query("SELECT * FROM users WHERE role != 'admin' ORDER BY name ASC");
            return $query->fetchAll();
        }

        // 2. Filter Rentals by Status
        public function listAllRentals($statusFilter = '', $search = '') {
    $db = Database::connect();
    try {
        // Base SQL with JOINs
        $sql = "SELECT r.*, u.name as client_name, p.name as bike_name 
                FROM rentals r 
                JOIN users u ON r.user_id = u.id 
                JOIN products p ON r.product_id = p.id 
                WHERE 1=1"; // 1=1 makes adding conditions easy
        
        $params = [];

        // Filter by Status
        if (!empty($statusFilter)) {
            $sql .= " AND r.status = :status";
            $params['status'] = $statusFilter;
        }

        // Search by Client Name OR Bike Name
        if (!empty($search)) {
            $sql .= " AND (u.name LIKE :search OR p.name LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        $query = $db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll();
    } catch (Exception $e) { 
        die('Error: ' . $e->getMessage()); 
    }
}
    public function listAllProducts($search = '', $statusFilter = '') {
    $db = Database::connect();
    try {
        $sql = "SELECT * FROM products WHERE 1=1"; // 1=1 makes adding conditions easier
        $params = [];

        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
            $params['search'] = "%$search%";
        }

        if (!empty($statusFilter)) {
            $sql .= " AND status = :status";
            $params['status'] = $statusFilter;
        }

        $sql .= " ORDER BY id DESC";
        $query = $db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll();
    } catch (Exception $e) { die('Error: ' . $e->getMessage()); }
}  
}
?>