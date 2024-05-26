<?php
session_start();

require 'includes/auth.php';

if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Load completed orders from JSON file
$completedOrdersFile = 'assets/data/completed_orders.json';
$completedOrdersData = file_get_contents($completedOrdersFile);
$completedOrders = json_decode($completedOrdersData, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Completed Orders</h1>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (!empty($completedOrders['orders'])): ?>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completedOrders['orders'] as $order): ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($order['product_name']); ?></p>
                                    <p class="text-gray-600 whitespace-no-wrap">Rs <?php echo htmlspecialchars($order['product_price']); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($order['name']); ?></p>
                                    <p class="text-gray-600 whitespace-no-wrap"><?php echo htmlspecialchars($order['email']); ?></p>
                                    <p class="text-gray-600 whitespace-no-wrap"><?php echo htmlspecialchars($order['phone']); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($order['timestamp'] ?? 'N/A'); ?></p> <!-- Handle missing timestamp -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-700">No completed orders found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
