<?php
require_once __DIR__ . '/../../controller/admin/StudentsPageController.php';

$viewData = StudentsPageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/students.php';
