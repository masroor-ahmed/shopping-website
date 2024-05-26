<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Shopping Store'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body>
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4 flex items-center justify-between">
            <a href="index.php" class="text-xl font-bold">My Shopping Store</a>
            <nav class="hidden md:block">
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:text-blue-500">Home</a></li>
                    <li><a href="#" class="hover:text-blue-500">Shop</a></li>
                    <li><a href="#" class="hover:text-blue-500">About</a></li>
                    <li><a href="#" class="hover:text-blue-500">Contact</a></li>
                </ul>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="#" class="hover:text-blue-500">
                    <i data-feather="shopping-cart"></i>
                </a>
                <a href="#" class="hover:text-blue-500">
                    <i data-feather="user"></i>
                </a>
            </div>
            <div class="block md:hidden">
                <button id="menu-toggle" class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-500 hover:text-blue-500 hover:border-blue-500">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <ul class="flex flex-col space-y-4 mt-4">
                <li><a href="index.php" class="block px-4 py-2 rounded hover:bg-gray-200">Home</a></li>
                <li><a href="#" class="block px-4 py-2 rounded hover:bg-gray-200">Shop</a></li>
                <li><a href="#" class="block px-4 py-2 rounded hover:bg-gray-200">About</a></li>
                <li><a href="#" class="block px-4 py-2 rounded hover:bg-gray-200">Contact</a></li>
            </ul>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <!-- Main Content Goes Here -->
    </main>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        feather.replace();
    </script>
</body>
</html>
