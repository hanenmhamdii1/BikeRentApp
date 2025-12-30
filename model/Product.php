<?php
class Product {
    private ?int $id;
    private ?string $name;
    private ?string $type;
    private ?float $price;
    private ?string $description;
    private ?string $image;
    private ?int $owner_id;

    public function __construct($id, $name, $type, $price, $description, $image, $owner_id) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
        $this->owner_id = $owner_id;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getType() { return $this->type; }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
}
?>