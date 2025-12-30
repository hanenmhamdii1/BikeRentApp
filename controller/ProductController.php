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

    public function listAllProducts() {
        $db = Database::connect();
        try {
            $query = $db->query("SELECT * FROM products WHERE status = 'available' ORDER BY id DESC");
            return $query->fetchAll();
        } catch (Exception $e) { die('Error: ' . $e->getMessage()); }
    }

    public function getProductById($id) {
        $db = Database::connect();
        $query = $db->prepare("SELECT * FROM products WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    public function updateProduct($id, $name, $type, $price, $description, $image) {
        $db = Database::connect();
        $sql = "UPDATE products SET name = :name, type = :type, price_per_day = :price, 
                description = :description, image_url = :image WHERE id = :id";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'name' => $name,
                'type' => $type,
                'price' => $price,
                'description' => $description,
                'image' => $image
            ]);
            return true;
        } catch (Exception $e) { die('Error: ' . $e->getMessage()); }
    }

    public function deleteProduct($id) {
        $db = Database::connect();
        try {
            $query = $db->prepare("DELETE FROM products WHERE id = :id");
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) { die('Error: ' . $e->getMessage()); }
    }
}
?>