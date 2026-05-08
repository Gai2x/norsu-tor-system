<?php
require_once __DIR__ . '/../../controller/admin/RequestsPageController.php';

$viewData = RequestsPageController::load();
extract($viewData);

include __DIR__ . '/../../view/admin/requests.php';
