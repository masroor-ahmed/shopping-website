<?php
// Check if the user is authenticated
session_start();
require 'includes/auth.php';

if (!isAuthenticated()) {
    // Redirect to login page or show an error message
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the product ID to delete
    $productIdToDelete = isset($_POST['product_id']) ? $_POST['product_id'] : null;

    if ($productIdToDelete) {
        // Read the JSON file
        $jsonData = file_get_contents('assets/data/products.json');
        $data = json_decode($jsonData, true);

        // Find the product to delete
        foreach ($data['products'] as $key => $product) {
            if ($product['id'] === $productIdToDelete) {
                // Remove the product from the array
                unset($data['products'][$key]);
                break;
            }
        }

        // Rewrite the JSON file with the modified data
        file_put_contents('assets/data/products.json', json_encode($data, JSON_PRETTY_PRINT));
    }
}
