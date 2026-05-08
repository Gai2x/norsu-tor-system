<?php
require_once __DIR__ . '/../../controller/admin/SchedulePageController.php';

$viewData = SchedulePageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/schedule.php';
