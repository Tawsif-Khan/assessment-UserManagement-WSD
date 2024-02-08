<?php
include('db.php');

// Define SQL for creating the users table
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL
)
";

// Execute SQL to create the users table
try {
    $pdo->exec($sql);
    echo "Users table created successfully";
} catch (PDOException $e) {
    die("Error creating users table: " . $e->getMessage());
}
?>
