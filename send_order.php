<?php
// Receive form data
$productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
$productName = isset($_POST['product_name']) ? $_POST['product_name'] : null;
$productPrice = isset($_POST['product_price']) ? $_POST['product_price'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$phone = isset($_POST['phone']) ? $_POST['phone'] : null;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
$address = isset($_POST['address']) ? $_POST['address'] : null;

// Store order data in JSON file
$orderData = [
    'product_id' => $productId,
    'product_name' => $productName,
    'product_price' => $productPrice,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'quantity' => $quantity,
    'address' => $address,
    'timestamp' => date('Y-m-d H:i:s')
];

$jsonFile = 'assets/data/orders.json';
if (!file_exists($jsonFile)) {
    // Create the file if it doesn't exist
    file_put_contents($jsonFile, json_encode(['orders' => []]));
}

$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);
$data['orders'][] = $orderData;
file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

// Send email to admin using PHPMailer
require 'includes/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'sendermail@gmail.com';
$mail->Password = 'Your mail app password'; //App password, not actual password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Send email to admin with order details
$mail->setFrom('sendermail@gmail.com', 'Shop me');
$mail->addAddress('adminmail@gmail.com', 'Admin');
$mail->isHTML(true);
$mail->Subject = 'New Order Received';
$mail->Body = '<div style="font-family: Arial, sans-serif; background-color: #f2f2f2; padding: 20px;">';
$mail->Body .= '<h1 style="color: #333;">New Order Details</h1>';
$mail->Body .= '<p style="color: #666; margin-bottom: 10px;">Product: ' . $productName . '</p>';
$mail->Body .= '<p style="color: #666; margin-bottom: 10px;">Price: $' . $productPrice . '</p>';
$mail->Body .= '<p style="color: #666; margin-bottom: 10px;">Quantity: ' . $quantity . '</p>';
$mail->Body .= '<p style="color: #666; margin-bottom: 10px;">Name: ' . $name . '</p>';
$mail->Body .= '<p style="color: #666; margin-bottom: 10px;">Email: ' . $email . '</p>';
$mail->Body .= '<p style="color: #666; margin-bottom: 10px;">Phone: ' . $phone . '</p>';
$mail->Body .= '<p style="color: #666; margin-bottom: 10px;">Address: ' . $address . '</p>';
$mail->Body .= '</div>';


if (!$mail->send()) {
    echo 'Error sending email to admin: ' . $mail->ErrorInfo;
} else {
    echo 'Order details sent to admin successfully!';
}

$mail->clearAddresses(); // Clear previous addresses
$mail->addAddress($email, $name);
$mail->Subject = 'Order Confirmation';

$userBody = '<div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #e0e0e0;">';
$userBody .= '<div style="background-color: #333; color: #fff; padding: 10px 0; text-align: center; border-radius: 10px 10px 0 0;"><h2>Shop me</h2></div>';
$userBody .= '<h1 style="color: #333; margin-bottom: 20px; text-align: center;">Thank you for your order!</h1>';
$userBody .= '<p style="color: #666; margin-bottom: 10px;">Dear ' . $name . ',</p>';
$userBody .= '<p style="color: #666; margin-bottom: 10px;">We have received your order for the following product:</p>';
$userBody .= '<p style="color: #666; margin-bottom: 10px;"><strong>Product:</strong> ' . $productName . '</p>';
$userBody .= '<p style="color: #666; margin-bottom: 10px;"><strong>Price:</strong> $' . $productPrice . '</p>';
$userBody .= '<p style="color: #666; margin-bottom: 10px;"><strong>Quantity:</strong> ' . $quantity . '</p>';
$userBody .= '<p style="color: #666; margin-bottom: 10px;"><strong>Total:</strong> $' . ($productPrice * $quantity) . '</p>';
$userBody .= '<p style="color: #666; margin-bottom: 20px;">We will process your order shortly and keep you updated on the shipping status.</p>';
$userBody .= '<p style="color: #666; margin-bottom: 10px;">Thank you for shopping with us!</p>';
$userBody .= '<p style="color: #666; text-align: center;">Best regards,<br>Shop me</p>';
$userBody .= '</div>';

$mail->Body = $userBody;




if (!$mail->send()) {
    echo 'Error sending email to user: ' . $mail->ErrorInfo;
} else {
    echo 'Order confirmation sent to user successfully!';
}
?>
