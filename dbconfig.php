<?php
// Replace the database credentials with your actual database information
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'keyboard_control';

// Create a database connection using mysqli_connect
$connection = mysqli_connect($host, $username, $password, $dbname);

// Check if the connection was successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>