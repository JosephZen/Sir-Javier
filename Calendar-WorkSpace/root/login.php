<?php require 'config.php'; if (isset($_SESSION['user_id'])) header('Location: index.php'); ?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Team Calendar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white">Login</h2>
        <form id="login-form">
            <input type="hidden" name="action" value="login">
            <div class="mb-4">
                <input type="text" name="username" placeholder="Username" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white" required>
            </div>
            <div class="mb-6">
                <input type="password" name="password" placeholder="Password" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700">Login</button>
        </form>
        <p class="mt-4 text-center"><a href="register.php" class="text-indigo-600">Register</a></p>
        <div id="message"></div>
    </div>
    <script src="assets/js/auth.js"></script>
</body>
</html>