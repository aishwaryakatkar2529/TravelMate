<?php
session_start();

$host = "localhost";
$db   = "travelmate";
$user = "tmuser";
$pass = "TravelMate@123";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

define("BASE_PATH", "/var/www/html/travelmate/");
define("UPLOAD_PATH", BASE_PATH . "uploads/packages/");
define("BASE_URL", "http://65.2.169.91/travelmate/");
define("UPLOAD_URL", BASE_URL . "uploads/packages/");
?>