<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../model/Product.php'); // Ensure this path is correct

class ProductController {
    
public function addProduct(Product $product) {
    $db = Database::connect();
    $sql = "INSERT INTO products (name, type, price_per_day, description, image_url, owner_id, status) 
            VALUES (:name, :type, :price, :description, :image, :owner_id, :status)";
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'name'        => $product->getName(),
            'type'        => $product->getType(),
            'price'       => $product->getPrice(),
            'description' => $product->getDescription(),
            'image'       => $product->getImage(),
            'owner_id'    => $product->getOwnerId(),
            'status'      => $product->getStatus()
        ]);
        return true;
    } catch (Exception $e) { 
        die('Error: ' . $e->getMessage()); 
    }
}

    
    public function getProductById($id) {
        $db = Database::connect();
        $query = $db->prepare("SELECT * FROM products WHERE id = ?");
        $query->execute([$id]);
        $p = $query->fetch();

        if ($p) {
            return new Product(
                $p['id'], $p['name'], $p['type'], 
                $p['price_per_day'], $p['description'], 
                $p['image_url'], $p['owner_id'], $p['status']
            );
        }
        return null;
    }

    public function listAllProducts($search = '', $statusFilter = '') {
        $db = Database::connect();
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
            $params['search'] = "%$search%";
        }
        if (!empty($statusFilter)) {
            $sql .= " AND status = :status";
            $params['status'] = $statusFilter;
        }

        $query = $db->prepare($sql . " ORDER BY id DESC");
        $query->execute($params);
        $rows = $query->fetchAll();

        $productList = [];
        foreach ($rows as $p) {
            $productList[] = new Product(
                $p['id'], $p['name'], $p['type'], 
                $p['price_per_day'], $p['description'], 
                $p['image_url'], $p['owner_id'], $p['status']
            );
        }
        return $productList;
    }
public function updateProduct(Product $product) {
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
        return $query->execute([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'type' => $product->getType(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'image' => $product->getImage(),
            'status' => $product->getStatus()
        ]);
    } catch (Exception $e) { return false; }
}

}
?>