<?php
include_once(__DIR__ . '/../config.php');

class RentController {
    public function createRent($userId, $productId, $startDate, $endDate, $totalPrice) {
        $db = Database::connect();
        try {
            $db->beginTransaction();

            // 1. Insert Rental Record
            $sql = "INSERT INTO rentals (user_id, product_id, start_date, end_date, total_price, status) 
                    VALUES (:uid, :pid, :start, :end, :total, 'active')";
            $query = $db->prepare($sql);
            $query->execute([
                'uid' => $userId,
                'pid' => $productId,
                'start' => $startDate,
                'end' => $endDate,
                'total' => $totalPrice
            ]);

            // 2. Update Product Status to 'rented'
            $updateSql = "UPDATE products SET status = 'rented' WHERE id = :pid";
            $updateQuery = $db->prepare($updateSql);
            $updateQuery->execute(['pid' => $productId]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            die('Error: ' . $e->getMessage());
        }
    }
}
?>