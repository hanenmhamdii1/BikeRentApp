<?php
session_start();
include_once '../../Controller/ProductController.php';

if (isset($_GET['id'])) {
    $pc = new ProductController();
    $product = $pc->getProductById($_GET['id']);
    if ($_SESSION['user_id'] == $product['owner_id']) {
        $pc->deleteProduct($_GET['id']);
    }
}
header('Location: list_product.php');
?>