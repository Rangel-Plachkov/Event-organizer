<?php
if(empty($_POST["username"])){
    die("Username is required");
}
if(empty($_POST["name"])){
    die("name is required");
}
$birthdate = $_POST["birthdate"];
$date_parts = explode('-', $birthdate);
if (count($date_parts) !== 3 || !checkdate($date_parts[1], $date_parts[2], $date_parts[0])) {
    die("Invalid birthdate format. Please use DD-MM-YYYY.");
}
if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address");
}
if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["confirm-password"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO User (username, email, name, birthdate, password)
                                                    VALUES (?, ?, ?, ?, ?)";
$stmt=$mysqli->prepare($sql);
if(!$stmt){
    die("SQL error :".$mysqli->error);
}
$stmt->bind_param("sssss", $_POST["username"],
                            $_POST["email"],
                            $_POST["name"],
                            $_POST["birthdate"],
                            $password_hash);
if ($stmt->execute()) {
    echo "Data successfully inserted!";
} else {
    die("Error executing query: " . $stmt->error);
}
$stmt->close();
$mysqli->close();
print_r($_POST);
var_dump($password_hash);