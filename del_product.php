<?php
// Check if the user is authenticated
session_start();
require 'includes/auth.php';

if (!isAuthenticated()) {
    // Redirect to login page or show an error message
    header('Location: login.php');
    exit;
}

$success = false;
$error = null;
$data = []; // Initialize $data as an empty array

// Function to delete a product
function deleteProduct($productId) {
    $jsonData = file_get_contents('assets/data/products.json');
    $data = json_decode($jsonData, true);

    // Find the index of the product to delete
    $index = null;
    foreach ($data['products'] as $key => $product) {
        if ($product['id'] === $productId) {
            $index = $key;
            // Delete the associated image from the server's directory
            $imagePath = 'uploads/product-images/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            break;
        }
    }

    // If product found, remove it from the array
    if ($index !== null) {
        unset($data['products'][$index]);

        // Reindex the array to prevent gaps
        $data['products'] = array_values($data['products']);

        // Save the updated data back to the JSON file
        file_put_contents('assets/data/products.json', json_encode($data, JSON_PRETTY_PRINT));
        return true;
    }

    return false; // Product not found
}

// Check if a delete request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    if (deleteProduct($productId)) {
        $success = true;
    } else {
        $error = 'Failed to delete product. Product not found.';
    }
}

// Load product data
$jsonData = file_get_contents('assets/data/products.json');
if ($jsonData !== false) {
    $data = json_decode($jsonData, true);
    if (!isset($data['products'])) {
        $data['products'] = [];
    }
} else {
    $error = 'Failed to load product data.';
}
?>

<?php include 'header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Admin Panel</h1>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            Product deleted successfully!
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <table class="border-collapse w-full">
        <thead>
            <tr>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Product Name</th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Description</th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Price</th>
                <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['products'] as $product): ?>
                <tr class="text-center">
                    <td class="p-3 border border-gray-300"><?php echo $product['name']; ?></td>
                    <td class="p-3 border border-gray-300"><?php echo $product['description']; ?></td>
                    <td class="p-3 border border-gray-300"><?php echo $product['price']; ?></td>
                    <td class="p-3 border border-gray-300">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete_product" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete Product</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="logout.php" class="bg-red-500 hover:bg-red
