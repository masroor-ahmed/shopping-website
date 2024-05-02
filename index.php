<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Showcase</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Product Showcase</h1>

        <!-- Search input field -->
        <input type="text" id="searchInput" class="w-full border border-gray-400 p-2 rounded mb-4" placeholder="Search products">

        <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Product cards will be dynamically generated here -->
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
