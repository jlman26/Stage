<?php
// Database connection parameters
$servername = "mysql";  // Replace with your server name
$username = "root";     // Replace with your MySQL username
$password = "root";     // Replace with your MySQL password
$dbname = "Quiz";       // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
