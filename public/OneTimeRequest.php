<?php
require_once __DIR__ . '/../controller/user/OneTimeRequestPageController.php';

$viewData = OneTimeRequestPageController::load();
extract($viewData);

include __DIR__ . '/../view/public/oneTimeRequest.php';
