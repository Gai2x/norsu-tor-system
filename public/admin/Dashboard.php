<?php
require_once __DIR__ . '/../../controller/admin/DashboardPageController.php';

$viewData = DashboardPageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/dashboard.php';
