<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /invoice/index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

try {
    $db = Connection::getInstance()->getConnection();
    
    // Get invoice details
    $stmt = $db->prepare("
        SELECT * FROM invoices 
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    $invoice = $stmt->fetch();

    if (!$invoice) {
        header('Location: dashboard.php');
        exit;
    }

    // Get invoice items
    $stmt = $db->prepare("
        SELECT * FROM invoice_items 
        WHERE invoice_id = ?
    ");
    $stmt->execute([$id]);
    $items = $stmt->fetchAll();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Invoice - Invoice System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Invoice #<?= htmlspecialchars($invoice['id']) ?></h2>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="mb-3">Customer Details:</h6>
                        <div>
                            <strong>Name:</strong> <?= htmlspecialchars($invoice['customer_name']) ?>
                        </div>
                        <div>
                            <strong>Email:</strong> <?= htmlspecialchars($invoice['customer_email']) ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="mb-3">Invoice Details:</h6>
                        <div>
                            <strong>Invoice Date:</strong> <?= htmlspecialchars($invoice['invoice_date']) ?>
                        </div>
                        <div>
                            <strong>Due Date:</strong> <?= htmlspecialchars($invoice['due_date']) ?>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['description']) ?></td>
                                <td class="text-end"><?= htmlspecialchars($item['quantity']) ?></td>
                                <td class="text-end">$<?= number_format($item['price'], 2) ?></td>
                                <td class="text-end">$<?= number_format($item['total'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                <td class="text-end"><strong>$<?= number_format($invoice['total_amount'], 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <a href="#" class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>