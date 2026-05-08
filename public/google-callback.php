<?php

require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../controller/AuthController.php';
require_once __DIR__ . '/../controller/GoogleAuthController.php';

$conn = require __DIR__ . '/../config/database.php';
$googleConfig = require __DIR__ . '/../config/google.php';

$userModel = new UserModel($conn);
$authController = new AuthController($userModel);
$googleAuthService = new GoogleAuthService($googleConfig);
$googleAuthController = new GoogleAuthController($googleAuthService, $authController, $userModel);

$googleAuthController->handleCallback($_GET);
