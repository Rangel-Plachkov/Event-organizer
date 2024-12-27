<<?php
require_once __DIR__ . '/Controller/UserController.php';


use Controller\UserController;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/signup' && $requestMethod === 'POST') {
$userController = new UserController();
$userController->createUser();
exit();
}

if ($requestUri === '/signup' && $requestMethod === 'GET') {
include __DIR__ . '../View/templates/signUpForm.html';
exit();
}

// Ако пътят не съвпада
http_response_code(404);
echo "Page not found.";