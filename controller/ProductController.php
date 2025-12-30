<?php
include_once(__DIR__ . '/../config.php');

class ProductController {
    
    public function addProduct($name, $type, $price, $description, $image, $owner_id) {
        $db = Database::connect();
        $sql = "INSERT INTO products (name, type, price_per_day, description, image_url, owner_id) 
                VALUES (:name, :type, :price, :description, :image, :owner_id)";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'name' => $name,
                'type' => $type,
                'price' => $price,
                'description' => $description,
                'image' => $image,
                'owner_id' => $owner_id
            ]);
            return true;
        } catch (Exception $e) { die('Error: ' . $e->getMessage()); }
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

public function updateProduct($id, $name, $type, $price, $description, $image, $status) {
    $db = Database::connect();
    $sql = "UPDATE products SET 
            name = :name, 
            type = :type, 
            price_per_day = :price, 
            description = :description, 
            image_url = :image, 
            status = :status 
            WHERE id = :id";
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'price' => $price,
            'description' => $description,
            'image' => $image,
            'status' => $status
        ]);
        return true;
    } catch (Exception $e) { die('Error: ' . $e->getMessage()); }
}    public function deleteProduct($id) {
        $db = Database::connect();
        try {
            $query = $db->prepare("DELETE FROM products WHERE id = :id");
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) { die('Error: ' . $e->getMessage()); }
    }
}
?>