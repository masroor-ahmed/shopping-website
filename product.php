<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Product Details'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body>
    <?php
    // Fetch product data from JSON file based on the 'id' parameter
    $productId = isset($_GET['id']) ? $_GET['id'] : null;
    $productData = null;

    if ($productId) {
        $jsonData = file_get_contents('assets/data/products.json');
        $data = json_decode($jsonData, true);

        foreach ($data['products'] as $product) {
            if ($product['id'] == $productId) {
                $productData = $product;
                break;
            }
        }
    }

    if (!$productData) {
        // Handle case when product is not found
        header('Location: index.php');
        exit;
    }
    ?>

    <?php include 'header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <img src="uploads/product-images/<?php echo $productData['image']; ?>" alt="<?php echo $productData['name']; ?>" class="w-full object-cover object-center h-64 sm:h-96">
            <div class="p-4">
                <h1 class="text-3xl font-bold mb-2"><?php echo $productData['name']; ?></h1>
                <p class="text-gray-700 mb-4"><?php echo $productData['description']; ?></p>
                <p class="text-2xl font-bold mb-4">$<?php echo $productData['price']; ?></p>
                <button id="buy-now" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Buy Now
                </button>
            </div>
        </div>
    </div>

    <!-- Buy Now Modal -->
    <div id="buy-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-lg relative">
                <button class="absolute top-2 right-2 text-gray-500 hover:text-red-500" id="close-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="p-6 max-w-md">
                    <h2 class="text-2xl font-bold mb-4">Buy Now</h2>
                    <form id="buy-form">
                        <input type="hidden" name="product_id" value="<?php echo $productData['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $productData['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $productData['price']; ?>">

                        <div class="mb-4">
                            <label for="name" class="block font-bold mb-2">Name</label>
                            <input type="text" name="name" id="name" class="w-full border border-gray-400 p-2 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block font-bold mb-2">Email</label>
                            <input type="email" name="email" id="email" class="w-full border border-gray-400 p-2 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block font-bold mb-2">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="w-full border border-gray-400 p-2 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="block font-bold mb-2">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="w-full border border-gray-400 p-2 rounded" required min="1" value="1">
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block font-bold mb-2">Address</label>
                            <textarea name="address" id="address" class="w-full border border-gray-400 p-2 rounded" required></textarea>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>

    <?php include 'footer.php'; ?>
</body>
</html>
