<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $quantity = $_POST['quantity'];
    $address = $_POST['address'];

    // Set timezone to Pakistan Standard Time
    date_default_timezone_set('Asia/Karachi');

    $orderData = [
        'product_id' => $productId,
        'product_name' => $productName,
        'product_price' => $productPrice,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'quantity' => $quantity,
        'address' => $address,
        'timestamp' => date('Y-m-d H:i:s')  // Add timestamp here
    ];

    $jsonData = file_get_contents('assets/data/orders.json');
    $data = json_decode($jsonData, true);

    $data['orders'][] = $orderData;

    file_put_contents('assets/data/orders.json', json_encode($data));

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
