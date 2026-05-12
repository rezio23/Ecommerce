<?php
require '../../includes/db.php';

try {
    $pdo->exec("ALTER TABLE products ADD COLUMN stock INT NOT NULL DEFAULT 0");
    echo "Stock column added to products table." . PHP_EOL;
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false || strpos($e->getMessage(), '1060') !== false) {
        echo "Stock column already exists." . PHP_EOL;
    } else {
        echo "Error: " . $e->getMessage() . PHP_EOL;
    }
}
