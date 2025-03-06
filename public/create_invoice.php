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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
        }
        
        .sidebar .nav-link.active {
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="mb-4 px-3">
                        <h2 class="h4">Invoice System</h2>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="invoices.php">
                                <i class="bi bi-file-text me-2"></i>
                                Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="customers.php">
                                <i class="bi bi-people me-2"></i>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Create New Invoice</h1>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="save_invoice.php" method="POST">
                            <!-- Customer Information -->
                            <div class="mb-4">
                                <h4 class="card-title mb-3">Customer Information</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_name" class="form-label">Customer Name</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_email" class="form-label">Customer Email</label>
                                        <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Details -->
                            <div class="mb-4">
                                <h4 class="card-title mb-3">Invoice Details</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="invoice_date" class="form-label">Invoice Date</label>
                                        <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Items -->
                            <div class="mb-4">
                                <h4 class="card-title mb-3">Invoice Items</h4>
                                <div id="invoice-items">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Description</label>
                                            <input type="text" name="items[0][description]" class="form-control" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" name="items[0][quantity]" class="form-control" required min="1">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Price</label>
                                            <input type="number" name="items[0][price]" class="form-control" required step="0.01">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-item" class="btn btn-success">Add Item</button>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Create Invoice</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('add-item').addEventListener('click', function() {
            const itemsContainer = document.getElementById('invoice-items');
            const itemCount = itemsContainer.children.length;
            
            const newItem = document.createElement('div');
            newItem.className = 'row mb-3';
            newItem.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label">Description</label>
                    <input type="text" name="items[${itemCount}][description]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" class="form-control" required min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="items[${itemCount}][price]" class="form-control" required step="0.01">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            `;
            
            itemsContainer.appendChild(newItem);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.row').remove();
            }
        });
    </script>
</body>
</html>