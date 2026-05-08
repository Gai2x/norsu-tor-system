<?php

require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../controller/AuthController.php';

$conn = require __DIR__ . '/../config/database.php';

$userModel = new UserModel($conn);
$authController = new AuthController($userModel);
$result = $authController->createGoogleStudent($_POST);

if (!empty($result['redirect'])) {
    header('Location: ' . $result['redirect']);
    exit();
}

if (!empty($result['errors'])) {
    print_r($result['errors']);
}
