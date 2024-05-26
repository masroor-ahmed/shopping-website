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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;

    // Check if all required fields are provided
    if (empty($name) || empty($description) || empty($price)) {
        $error = 'Please provide all required fields.';
    } else {
        $image = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK ? $_FILES['image'] : null;

        if ($image) {
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
                ];

                $jsonData = file_get_contents('assets/data/products.json');
                $data = json_decode($jsonData, true);
                $data['products'][] = $productData;
                file_put_contents('assets/data/products.json', json_encode($data, JSON_PRETTY_PRINT));

                $success = true;
            } else {
                $error = 'Error uploading image.';
            }
        } else {
            $error = 'Please select an image file.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Admin Panel</h1>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            Product added successfully!
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form id="add-product-form" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="mb-4">
            <label for="name" class="block font-bold mb-2">Product Name</label>
            <input type="text" name="name" id="name" class="w-full border border-gray-400 p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-bold mb-2">Description</label>
            <textarea name="description" id="description" class="w-full border border-gray-400 p-2 rounded" required></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block font-bold mb-2">Price</label>
            <input type="number" name="price" id="price" class="w-full border border-gray-400 p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="image" class="block font-bold mb-2">Product Image</label>
            <input type="file" name="image" id="image" class="w-full border border-gray-400 p-2 rounded" required accept="image/*">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Save Product
        </button>
         <a href="orders.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View orders</a>
    </form>
    <a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block mt-4">Logout</a>
        <a href="del_product.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block mt-4">Delete Product</a>

</div>

<?php include 'footer.php'; ?>