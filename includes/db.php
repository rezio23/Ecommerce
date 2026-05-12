<?php

require_once __DIR__ . '/env.php';

// Load environment variables from project root
$envPath = realpath(__DIR__ . '/../.env');
if ($envPath && file_exists($envPath)) {
    loadEnv($envPath);
}

$host = env('DB_HOST', 'localhost');
$port = env('DB_PORT', '3306');
$db   = env('DB_NAME', '');
$user = env('DB_USER', '');
$pass = env('DB_PASS', '');
$charset = env('DB_CHARSET', 'utf8mb4');

if ($db === '' || $user === '') {
    http_response_code(500);
    exit('Database configuration is incomplete. Please check your .env file.');
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection error. Please check your settings in .env file.');
}
