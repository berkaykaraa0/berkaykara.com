<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require_once __DIR__ . '/config/database.php';
$db = Database::connect();
$stmt = $db->query("SELECT id, username, password FROM users WHERE username = 'admin'");
$user = $stmt->fetch();
if ($user) {
    echo "User found: " . $user['username'] . "\n";
    echo "Hash: " . $user['password'] . "\n";
    echo "Verifying Admin@123: " . (password_verify('Admin@123', $user['password']) ? "MATCH" : "NO MATCH") . "\n";
} else {
    echo "User admin not found.\n";
}
