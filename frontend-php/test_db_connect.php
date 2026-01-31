<?php
require_once 'includes/db.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "Successfully connected to the database via PHP!\n";
    
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(", ", $tables) . "\n";
    
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
