<?php
require_once __DIR__ . '/../../controller/admin/StudentEditPageController.php';

$viewData = StudentEditPageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/studentEdit.php';
