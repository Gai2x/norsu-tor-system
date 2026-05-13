<?php
session_start();

require_once __DIR__ . '/../database/Connection.php';
require_once __DIR__ . '/../model/UserRoleModel.php';
require_once __DIR__ . '/../model/NotificationModel.php';

UserRoleModel::requireAnyRole(UserRoleModel::all(), 'Login.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$userId = (int) ($_SESSION['user_id'] ?? 0);
$role = (string) ($_SESSION['role'] ?? '');

if ($userId <= 0 || $role === '') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$model = new NotificationModel(Connection::getInstance());
$notifications = $model->getForAccount($userId, $role);

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'unread' => count(array_filter($notifications, fn($item) => empty($item['is_read']))),
]);
