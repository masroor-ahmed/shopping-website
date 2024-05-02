<?php
session_start();

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    // User is already authenticated, redirect to admin page
    header('Location: admin.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    require 'includes/auth.php';

    if (isAuthenticated($username, $password)) {
        $_SESSION['authenticated'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Login</h1>
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-4">
                <label for="username" class="block font-bold mb-2">Username</label>
                <input type="text" name="username" id="username" class="w-full border border-gray-400 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full border border-gray-400 p-2 rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Login
            </button>
        </form>
    </div>
</body>
</html>