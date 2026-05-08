<?php
$page = "auth";
$bodyClass = "bg-gradient-to-br from-blue-800 via-blue-600 to-blue-500";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../controller/user/UserController.php';

$controller = new UserController();
$error = $controller->login($_POST);
$success = $_SESSION['login_success'] ?? null;
unset($_SESSION['login_success']);

include __DIR__ . '/../view/auth/login.php';
