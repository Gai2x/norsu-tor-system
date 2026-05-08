<?php
require_once __DIR__ . '/../../controller/admin/StudentViewPageController.php';

$viewData = StudentViewPageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/studentView.php';
