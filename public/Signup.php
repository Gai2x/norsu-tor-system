<?php
$page = "auth";
$bodyClass = "bg-gradient-to-br from-blue-800 via-blue-600 to-blue-500";

require_once '../controller/user/UserController.php';

$userController = new UserController();
$errors = $userController->register($_POST, $_SERVER['REQUEST_METHOD']);

include __DIR__ . '/../view/auth/signup.php';
