<?php
require_once __DIR__ . '/../../controller/superadmin/SuperAdminController.php';

$viewData = SuperAdminController::dashboard();
extract($viewData);
SuperAdminController::clearDashboardFlash();

include __DIR__ . '/../../view/superadmin/dashboard.php';
