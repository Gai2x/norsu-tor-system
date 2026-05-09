<?php
require_once __DIR__ . '/../../controller/superadmin/SuperAdminController.php';

$viewData = SuperAdminController::admins();
extract($viewData);

include __DIR__ . '/../../view/superadmin/admins.php';
