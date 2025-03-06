<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = new User();
    $authenticated = $user->authenticate($email, $password);

    if ($authenticated) {
        $_SESSION['user'] = $authenticated;
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: index.php?error=1');
        exit;
    }
}

header('Location: index.php');
exit;