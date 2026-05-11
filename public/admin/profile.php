<?php
require_once __DIR__ . '/../../controller/admin/AdminProfilePageController.php';

$viewData = AdminProfilePageController::load();
include __DIR__ . '/../../view/admin/profile.php';
