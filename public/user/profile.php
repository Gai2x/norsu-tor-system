<?php
require_once __DIR__ . '/../../controller/user/ProfilePageController.php';

$viewData = ProfilePageController::load();
extract($viewData);

include __DIR__ . '/../../view/user/profile.php';
