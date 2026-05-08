<?php
require_once __DIR__ . '/../../controller/admin/RequestViewPageController.php';

$viewData = RequestViewPageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/requestView.php';
