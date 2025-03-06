<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Invoice System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 bg-white w-64 shadow-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Invoice System</h2>
            </div>
            <nav class="mt-6">
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-700 bg-gray-100">
                    <span class="mx-3">Dashboard</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Invoices</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Customers</span>
                </a>
                <a href="logout.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 p-8">
            <h1 class="text-3xl font-bold mb-8">Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-4">Total Invoices</h3>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-4">Total Customers</h3>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-4">Total Revenue</h3>
                    <p class="text-3xl font-bold">$0.00</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>