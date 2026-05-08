<?php

require_once __DIR__ . '/../controller/auth/OtpController.php';

$controller = new OtpController();
$viewData = $controller->handle($_SERVER['REQUEST_METHOD'], $_POST);
extract($viewData);

include __DIR__ . '/../view/auth/verify-otp.php';
