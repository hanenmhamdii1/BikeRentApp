<?php
class Product {
    private ?int $id;
    private ?string $name;
    private ?string $type;
    private ?float $price;
    private ?string $description;
    private ?string $image;
    private ?int $owner_id;
    private ?string $status;

    public function __construct($id, $name, $type, $price, $description, $image, $owner_id, $status = 'available') {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
        $this->owner_id = $owner_id;
        $this->status = $status;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getType() { return $this->type; }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
    public function getOwnerId() { return $this->owner_id; }
    public function getStatus() { return $this->status; }

    // Setters
    public function setName(?string $name) { $this->name = $name; }
    public function setType(?string $type) { $this->type = $type; }
    public function setPrice(?float $price) { $this->price = $price; }
    public function setDescription(?string $description) { $this->description = $description; }
    public function setImage(?string $image) { $this->image = $image; }
    public function setStatus(?string $status) { $this->status = $status; }
}
?>