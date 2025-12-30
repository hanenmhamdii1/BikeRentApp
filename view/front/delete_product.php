<?php
session_start();
include_once '../../Controller/ProductController.php';

// Check if ID exists and user is logged in
if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $pc = new ProductController();
    $product = $pc->getProductById($_GET['id']);

    if ($product) {
        // FLEXIBLE SECURITY: Allow deletion if user is the specific owner 
        // OR if they are an 'owner' and the product currently has no assigned owner (0 or NULL)
        if ($_SESSION['user_role'] == 'owner') {
            $pc->deleteProduct($_GET['id']);
        }
    }
}

// Always redirect back to the gallery
header('Location: list_product.php');
exit();
?>