<?php
require 'config.php';
require 'includes/functions.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo isset($_COOKIE['dark-mode']) && $_COOKIE['dark-mode'] === 'dark' ? 'dark' : 'light'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Calendar Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen p-4 md:p-8 font-sans">
    <header class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Team Calendar - <?php echo getCurrentUser(); ?></h1>
        <div class="flex items-center space-x-4">
            <button id="view-month" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Month View</button>
            <button id="view-week" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Week View</button>
            <button id="view-day" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Day View</button>
            <button id="view-hour" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Hour View</button>
            <button id="mode-toggle" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg shadow">🌙/☀️</button>
            <button id="new-task-btn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">New Task</button>
            <button id="logout-btn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Logout</button>
        </div>
    </header>
    <div id="calendar-container" class="calendar-grid">
        <div class="calendar-cell">Mon<br><div class="mt-2">Task 1<br><div class="progress-bar"><div class="progress-fill" style="width: 50%;"></div></div></div></div>
        <div class="calendar-cell">Tue<br><div class="mt-2">Task 2<br><div class="progress-bar"><div class="progress-fill" style="width: 80%;"></div></div></div></div>
    </div>
    <div id="new-task-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 pointer-events-none">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
            <button id="close-modal-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Create New Task</h3>
            <form id="new-task-form">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Title</label>
                    <input type="text" name="title" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Task title">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Start Time</label>
                    <input type="datetime-local" name="start_time" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-300">End Time</label>
                    <input type="datetime-local" name="end_time" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Progress (%)</label>
                    <input type="number" name="progress" min="0" max="100" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 dark:text-gray-300">Status</label>
                    <select name="status" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="ongoing">On-going</option>
                        <option value="almost-finished">Almost Finished</option>
                        <option value="help-requested">Request Help</option>
                        <option value="finished">Finished</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">Create Task</button>
            </form>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>