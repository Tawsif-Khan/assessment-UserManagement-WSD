<?php
include './lib/Database.php';

// Define SQL for creating the users table
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL
)
";
$password = password_hash('password', PASSWORD_DEFAULT);
$insert = "INSERT INTO users (username, email, password, role) VALUES ('Admin', 'admin@admin.com', '$password', 'admin')";
// Execute SQL to create the users table
try {
    $db = new Database();
    $db->pdo->exec($sql);
    $db->pdo->exec($insert);
    echo "Users table created successfully\n";
    echo "Admin created successfully\n";
} catch (PDOException $e) {
    die("Error creating users table: " . $e->getMessage());
}
