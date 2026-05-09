<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../controller/user/HomeController.php';

$controller = new HomeController();
$viewData = $controller->index();
extract($viewData);

include __DIR__ . '/../view/home.php';
