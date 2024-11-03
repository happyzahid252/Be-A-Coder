<?php
// db.php
$host = "localhost";
$dbname = "9.code"; // Replace with your database name
$username = "root";     // Replace with your database username
$password = "";     // Replace with your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
