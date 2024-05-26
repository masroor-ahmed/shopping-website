<?php

session_start();

require 'includes/auth.php';

if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Define your shop's coordinates
$shopLat = 31.4475;
$shopLon = 73.6972;

// Function to get coordinates from address using Mapbox API
function getCoordinates($address, $mapboxToken) {
    $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . urlencode($address) . ".json?access_token=" . $mapboxToken;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    if (isset($data['features'][0]['center'])) {
        return $data['features'][0]['center'];
    }
    return [0, 0]; // Default coordinates if address is not found
}

// Function to calculate distance using Haversine formula
function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Radius of the earth in km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c; // Distance in km
}

// Read orders from JSON file
$jsonFile = 'assets/data/orders.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);
$orders = $data['orders'];

// Get Mapbox token from environment or configuration
$mapboxToken = 'Replace this with your Mapbox API token';

// Calculate distances for each order
foreach ($orders as &$order) {
    list($lon, $lat) = getCoordinates($order['address'], $mapboxToken);
    $order['distance'] = haversineDistance($shopLat, $shopLon, $lat, $lon);
}

// Sort orders by distance
usort($orders, function($a, $b) {
    return $a['distance'] - $b['distance'];
});

// Handle marking order as complete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order_id'])) {
    $completeOrderId = $_POST['complete_order_id'];
    $completedOrder = null;

    foreach ($orders as $key => $order) {
        if ($order['product_id'] == $completeOrderId) {
            $completedOrder = $order;
            unset($orders[$key]);
            break;
        }
    }

    if ($completedOrder) {
        // Add completed order to completed_orders.json
        $completedFile = 'assets/data/completed_orders.json';
        if (!file_exists($completedFile)) {
            file_put_contents($completedFile, json_encode(['orders' => []]));
        }
        $completedData = json_decode(file_get_contents($completedFile), true);
        $completedData['orders'][] = $completedOrder;
        file_put_contents($completedFile, json_encode($completedData, JSON_PRETTY_PRINT));

        // Update orders.json
        $data['orders'] = array_values($orders);
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

        header('Location: orders.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Pending Orders</h1>
        <a href="completed_orders.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View completed orders</a>
        <br>    
            <a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block mt-4">Logout</a>
        <?php if (!empty($orders)): ?>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Address</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Distance (km)</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($order['product_name']); ?></p>
                                <p class="text-gray-600 whitespace-no-wrap">$<?php echo htmlspecialchars($order['product_price']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($order['name']); ?></p>
                                <p class="text-gray-600 whitespace-no-wrap"><?php echo htmlspecialchars($order['email']); ?></p>
                                <p class="text-gray-600 whitespace-no-wrap"><?php echo htmlspecialchars($order['phone']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($order['address']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo number_format($order['distance'], 2); ?> km</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <form method="post">
                                    <input type="hidden" name="complete_order_id" value="<?php echo htmlspecialchars($order['product_id']); ?>">
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Mark as Complete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-700">No pending orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
