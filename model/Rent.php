<?php
class Rent {
    private ?int $id;
    private int $userId;
    private int $productId;
    private string $startDate;
    private string $endDate;
    private float $totalPrice;
    private string $status;

    public function __construct($id, $userId, $productId, $startDate, $endDate, $totalPrice, $status = 'pending') {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalPrice = $totalPrice;
        $this->status = $status;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getProductId() { return $this->productId; }
    public function getTotalPrice() { return $this->totalPrice; }
}
?>