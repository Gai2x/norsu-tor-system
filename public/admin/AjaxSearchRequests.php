<?php
require_once __DIR__ . '/../../database/Connection.php';
require_once __DIR__ . '/../../controller/admin/AdminController.php';

$conn = Connection::getInstance();
AdminController::ajaxSearchRequests($conn);
