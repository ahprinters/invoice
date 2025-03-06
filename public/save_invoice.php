<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_invoice.php');
    exit;
}

try {
    $db = Connection::getInstance()->getConnection();
    $db->beginTransaction();

    // Insert invoice
    $stmt = $db->prepare("
        INSERT INTO invoices (customer_name, customer_email, invoice_date, due_date)
        VALUES (:customer_name, :customer_email, :invoice_date, :due_date)
    ");

    $stmt->execute([
        'customer_name' => $_POST['customer_name'],
        'customer_email' => $_POST['customer_email'],
        'invoice_date' => $_POST['invoice_date'],
        'due_date' => $_POST['due_date']
    ]);

    $invoiceId = $db->lastInsertId();
    $totalAmount = 0;

    // Insert invoice items
    $stmt = $db->prepare("
        INSERT INTO invoice_items (invoice_id, description, quantity, price, total)
        VALUES (:invoice_id, :description, :quantity, :price, :total)
    ");

    foreach ($_POST['items'] as $item) {
        $itemTotal = $item['quantity'] * $item['price'];
        $totalAmount += $itemTotal;

        $stmt->execute([
            'invoice_id' => $invoiceId,
            'description' => $item['description'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total' => $itemTotal
        ]);
    }

    // Update invoice total
    $stmt = $db->prepare("UPDATE invoices SET total_amount = :total WHERE id = :id");
    $stmt->execute([
        'total' => $totalAmount,
        'id' => $invoiceId
    ]);

    $db->commit();
    header('Location: dashboard.php?success=1');
    exit;

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    header('Location: create_invoice.php?error=1');
    exit;
}