<?php
$host = "localhost";
$user = "root";
$password = "root";
$database = "Event-organizer";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
return $conn;

///\JetBrains\PhpStorm\Deprecated::