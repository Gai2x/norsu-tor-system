<?php
require_once __DIR__ . '/../../controller/superadmin/SuperAdminController.php';

$viewData = SuperAdminController::requests();
extract($viewData);

include __DIR__ . '/../../view/superadmin/requests.php';
