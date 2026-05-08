<?php

require_once __DIR__ . '/../controller/auth/ResetPasswordController.php';

$controller = new ResetPasswordController();
$viewData = $controller->handle($_SERVER['REQUEST_METHOD'], $_POST);
extract($viewData);

include __DIR__ . '/../view/auth/reset-password.php';
