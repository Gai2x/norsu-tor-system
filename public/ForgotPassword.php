<?php

require_once __DIR__ . '/../controller/auth/ForgotPasswordController.php';

$controller = new ForgotPasswordController();
$viewData = $controller->handle($_SERVER['REQUEST_METHOD'], $_POST);
extract($viewData);

include __DIR__ . '/../view/auth/forgot-password.php';
