<?php

// Database configuration
$dsn = 'mysql:host=localhost;dbname=user_management';
$username = 'root';
$password = 'Abcde12345#*';

// Establish database connection
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
