<?php
// Read configuration from config.ini
$config = parse_ini_file('config.ini', true);

// Retrieve database settings
$servername = $config['database']['servername'];
$username = $config['database']['username'];
$password = $config['database']['password'];
$database = $config['database']['dbname'];

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8");
?>
