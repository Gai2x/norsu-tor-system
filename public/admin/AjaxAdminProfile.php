<?php
require_once __DIR__ . '/../../database/Connection.php';
require_once __DIR__ . '/../../controller/admin/AdminController.php';

$conn = $GLOBALS['conn'] ?? null;
if (!$conn) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['success' => false, 'errors' => ['Database connection unavailable.']]);
    exit();
}

AdminController::ajaxAdminProfile($conn);
