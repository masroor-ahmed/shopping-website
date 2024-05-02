<?php
// Receive form data
$name = isset($_POST['name']) ? trim($_POST['name']) : null;
$description = isset($_POST['description']) ? trim($_POST['description']) : null;
$price = isset($_POST['price']) ? trim($_POST['price']) : null;
$image = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK ? $_FILES['image'] : null;
// Retrieve other product fields

// Check if all required fields are provided
if (empty($name) || empty($description) || empty($price) || empty($image)) {
    echo 'Please provide all required fields.';
    exit;
}

// Handle image upload
$uploadDir = 'uploads/product-images/';
$imageName = uniqid() . '_' . $image['name'];
$uploadPath = $uploadDir . $imageName;

if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
    // Add product data to JSON file
    $productData = [
        'id' => uniqid(),
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'image' => $imageName,
        // Add other product fields
    ];

    $jsonData = file_get_contents('assets/data/products.json');
    $data = json_decode($jsonData, true);
    $data['products'][] = $productData;
    file_put_contents('assets/data/products.json', json_encode($data, JSON_PRETTY_PRINT));

    echo 'Product added successfully!';
} else {
    echo 'Error uploading image.';
}