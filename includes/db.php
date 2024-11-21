<?php
// db.php (Database Connection Setup)
$host = 'localhost';
$dbname = 'social_media_project'; // Replace with your DB name
$username = 'root'; // Default MySQL username in XAMPP
$password = ''; // Default MySQL password in XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
