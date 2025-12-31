<?php
include_once(__DIR__ . '/../config.php');

class RentController {

    public function createRent($userId, $productId, $startDate, $endDate, $totalPrice) {
        $db = Database::connect();
        try {
            $db->beginTransaction();
            
            $sql = "INSERT INTO rentals (user_id, product_id, start_date, end_date, total_price, status) 
                    VALUES (:uid, :pid, :start, :end, :total, 'active')";
            $query = $db->prepare($sql);
            $query->execute([
                'uid'   => $userId, 
                'pid'   => $productId, 
                'start' => $startDate, 
                'end'   => $endDate, 
                'total' => $totalPrice
            ]);
            
            $updateSql = "UPDATE products SET status = 'rented' WHERE id = :pid";
            $db->prepare($updateSql)->execute(['pid' => $productId]);
            
            $db->commit();
            return true;
        } catch (Exception $e) { 
            $db->rollBack(); 
            die('Error: ' . $e->getMessage()); 
        }
    }

    public function cancelRental($rentId) {
        $db = Database::connect();
        try {
            $db->beginTransaction();
            
            $stmt = $db->prepare("SELECT product_id FROM rentals WHERE id = ?");
            $stmt->execute([$rentId]);
            $pid = $stmt->fetchColumn();

            $sql = "UPDATE rentals SET status = 'cancelled' WHERE id = ?";
            $db->prepare($sql)->execute([$rentId]);

            $db->prepare("UPDATE products SET status = 'available' WHERE id = ?")->execute([$pid]);

            $db->commit();
            return true;
        } catch (Exception $e) { 
            $db->rollBack(); 
            return false; 
        }
    }

    public function updateRentalDates($rentId, $newStart, $newEnd, $newTotal) {
        $db = Database::connect();
        try {
            $sql = "UPDATE rentals SET start_date = ?, end_date = ?, total_price = ? WHERE id = ?";
            return $db->prepare($sql)->execute([$newStart, $newEnd, $newTotal, $rentId]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getRentalsByClient($userId) {
        $db = Database::connect();
        try {
            $sql = "SELECT r.*, p.name as bike_name, p.image_url, p.type 
                    FROM rentals r 
                    JOIN products p ON r.product_id = p.id 
                    WHERE r.user_id = :uid AND r.status != 'cancelled'
                    ORDER BY r.id DESC";
            $query = $db->prepare($sql);
            $query->execute(['uid' => $userId]);
            return $query->fetchAll(); 
        } catch (Exception $e) { 
            die('Error: ' . $e->getMessage()); 
        }
    }
} 

?>