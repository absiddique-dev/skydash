<?php
$host = 'localhost'; // Your database server hostname
$username = 'root'; // Your database username
$password = ''; // Your database password
$database = 'assamtet'; // Your database name

$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}