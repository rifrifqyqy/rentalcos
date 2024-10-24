<?php
// Database credentials
$host = 'localhost'; // Database host, usually 'localhost'
$username = 'root';  // Your database username
$password = '';      // Your database password
$database = 'rentalcos'; // The name of your database

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection jika gagal maka echo gagal
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    
}
// Set the character set to utf8
$conn->set_charset("utf8");
