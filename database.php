<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "Event-organizer";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
return $conn;