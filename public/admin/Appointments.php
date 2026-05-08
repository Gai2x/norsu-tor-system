<?php
require_once __DIR__ . '/../../controller/admin/AppointmentsPageController.php';

$viewData = AppointmentsPageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/appointments.php';
