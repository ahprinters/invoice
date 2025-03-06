<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Invoice System' ?></title>
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
                <a href="/invoice/public/dashboard.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Dashboard</span>
                </a>
                <a href="/invoice/public/invoices.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Invoices</span>
                </a>
                <a href="/invoice/public/customers.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Customers</span>
                </a>
                <a href="/invoice/public/logout.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100">
                    <span class="mx-3">Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 p-8">
            <?= $content ?? '' ?>
        </main>
    </div>
</body>
</html>