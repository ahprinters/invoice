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
    <title>Create Invoice - Invoice System</title>
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
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Dashboard</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-700 bg-gray-100">
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Create New Invoice</h1>
            </div>
            <form action="/invoice/public/save_invoice.php" method="POST" class="bg-white rounded-lg shadow-md p-6">
                <!-- Customer Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_name">
                                Customer Name
                            </label>
                            <input type="text" id="customer_name" name="customer_name" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_email">
                                Customer Email
                            </label>
                            <input type="email" id="customer_email" name="customer_email" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Invoice Details</h2>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="invoice_date">
                                Invoice Date
                            </label>
                            <input type="date" id="invoice_date" name="invoice_date" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="due_date">
                                Due Date
                            </label>
                            <input type="date" id="due_date" name="due_date" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Invoice Items</h2>
                    <div id="invoice-items">
                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                <input type="text" name="items[0][description]" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                                <input type="number" name="items[0][quantity]" required min="1"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                                <input type="number" name="items[0][price]" required step="0.01"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Add Item
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Create Invoice
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        document.getElementById('add-item').addEventListener('click', function() {
            const itemsContainer = document.getElementById('invoice-items');
            const itemCount = itemsContainer.children.length;
            
            const newItem = document.createElement('div');
            newItem.className = 'grid grid-cols-4 gap-4 mb-4';
            newItem.innerHTML = `
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <input type="text" name="items[${itemCount}][description]" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" required min="1"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                    <input type="number" name="items[${itemCount}][price]" required step="0.01"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-end">
                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove</button>
                </div>
            `;
            
            itemsContainer.appendChild(newItem);
        });

        document.getElementById('invoice-items').addEventListener('click', function(e) {
            if (e.target.matches('button') && e.target.textContent === 'Remove') {
                e.target.closest('.grid').remove();
            }
        });
    </script>
</body>
</html>