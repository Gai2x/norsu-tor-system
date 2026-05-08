<?php
require_once __DIR__ . '/../../controller/admin/AddSchedulePageController.php';

$viewData = AddSchedulePageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/addSchedule.php';
