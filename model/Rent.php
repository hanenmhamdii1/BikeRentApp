<?php
class Rent {
    private ?int $id;
    private int $userId;
    private int $productId;
    private string $startDate;
    private string $endDate;
    private float $totalPrice;
    private string $status;

    public function __construct($id, $userId, $productId, $startDate, $endDate, $totalPrice, $status = 'active') {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalPrice = $totalPrice;
        $this->status = $status;
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getProductId(): int { return $this->productId; }
    public function getStartDate(): string { return $this->startDate; }
    public function getEndDate(): string { return $this->endDate; }
    public function getTotalPrice(): float { return $this->totalPrice; }
    public function getStatus(): string { return $this->status; }

    public function setStartDate(string $date) { $this->startDate = $date; }
    public function setEndDate(string $date) { $this->endDate = $date; }
    public function setStatus(string $status) { $this->status = $status; }
    public function setTotalPrice(float $price) { $this->totalPrice = $price; }
}
?>