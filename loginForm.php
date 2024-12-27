<?php
$is_invalid = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";
    $username = isset($_POST["username"]) ? $mysqli->real_escape_string($_POST["username"]) : '';

    $sql = sprintf("SELECT * FROM user
                    WHERE username = '%s'", $username);

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();

    if ($user) {
        if(password_verify($_POST["password"], $user["password"])) {
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit();
        }
    }
    $is_invalid = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Organizer Login</title>
    <link rel="stylesheet" href="/View/css/styles.css">
</head>
<body>
<div class="login-container">
    <h1>Login to Event Organizer</h1>
    <?php if ($is_invalid) : ?>
        <p>Invalid username or password.</p>
    <?php endif; ?>
    <form  method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="/templates/forgotPasswordForm.html">Forgot Password?</a>
    <a href="/templates/signUpForm.html">Register</a>
</div>
</body>
</html>
