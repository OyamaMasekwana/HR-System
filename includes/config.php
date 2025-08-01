<?php
// includes/config.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "OM1710004";
$dbname = "company_hr";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/../classes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});