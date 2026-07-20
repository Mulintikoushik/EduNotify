<?php

$host = "localhost";
$username = "root";
$password = "mysql@2026";
$database = "result_alert_system";

// Create Database Connection
$conn = mysqli_connect($host, $username, $password, $database);
date_default_timezone_set('Asia/Kolkata');

// Check Connection
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Connection successful
?>