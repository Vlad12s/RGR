<?php
// db.php - підключення до БД
$dsn = "mysql:host=db;dbname=demo;charset=utf8mb4";
$user = "app";
$pass = "secret";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo "DB connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
