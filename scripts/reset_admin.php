<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    // Generate new password hash
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Update admin user password
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@example.com'");
    $stmt->execute([$password]);
    
    echo "Admin password has been reset successfully!\n";
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}