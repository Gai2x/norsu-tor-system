<?php
require_once __DIR__ . '/../../controller/superadmin/SuperAdminController.php';

$viewData = SuperAdminController::users();
extract($viewData);

include __DIR__ . '/../../view/superadmin/users.php';
