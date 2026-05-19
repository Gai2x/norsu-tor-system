<?php
session_start();

require_once __DIR__ . '/../database/Connection.php';
require_once __DIR__ . '/../model/UserRoleModel.php';
require_once __DIR__ . '/../model/NotificationModel.php';

UserRoleModel::requireAnyRole(UserRoleModel::all(), 'Login.php');

header('Content-Type: application/json');

$userId = (int) ($_SESSION['user_id'] ?? 0);
$role = (string) ($_SESSION['role'] ?? '');

if ($userId <= 0 || $role === '') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$model = new NotificationModel(Connection::getInstance());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');
    if ($action === 'mark_read') {
        $notificationId = trim($_POST['id'] ?? '');
        $success = $model->markAsRead($userId, $notificationId);
        $unread = $model->countUnreadForAccount($userId, $role);
        echo json_encode([
            'success' => $success,
            'id' => $notificationId,
            'unread' => $unread,
        ]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$notifications = $model->getForAccount($userId, $role);

define('NOTIFICATION_BASE_PATH', '/Norsu_Tor');

function resolveNotificationUrl(array $notification, string $role): string
{
    $type = $notification['type'] ?? 'info';

    if ($role === 'student') {
        if ($type === 'appointment') {
            return 'Appointments.php';
        }
        if ($type === 'request') {
            return 'Request.php';
        }
        return 'Dashboard.php';
    }

    if ($role === 'admin') {
        if ($type === 'appointment') {
            return NOTIFICATION_BASE_PATH . '/public/admin/Appointments.php';
        }
        if ($type === 'request') {
            return NOTIFICATION_BASE_PATH . '/public/admin/Requests.php';
        }
        return NOTIFICATION_BASE_PATH . '/public/admin/Dashboard.php';
    }

    if ($role === 'super_admin') {
        if ($type === 'request') {
            return NOTIFICATION_BASE_PATH . '/superadmin/requests';
        }
        if ($type === 'appointment') {
            return NOTIFICATION_BASE_PATH . '/superadmin/dashboard';
        }
        return NOTIFICATION_BASE_PATH . '/superadmin/dashboard';
    }

    return NOTIFICATION_BASE_PATH . '/Dashboard.php';
}

$notifications = array_map(function (array $notification) use ($role) {
    $notification['url'] = resolveNotificationUrl($notification, $role);
    return $notification;
}, $notifications);

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'unread' => count(array_filter($notifications, fn($item) => empty($item['is_read']))),
]);
