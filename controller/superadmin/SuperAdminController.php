<?php

require_once __DIR__ . '/SuperAdminPageController.php';
require_once __DIR__ . '/../../model/superadmin/SuperAdminModel.php';

class SuperAdminController
{
    private SuperAdminModel $model;
    private const DASHBOARD_PATH = '/Norsu_Tor/superadmin/dashboard';

    public function __construct(mysqli $conn)
    {
        $this->model = new SuperAdminModel($conn);
    }

    public static function dashboard(): array
    {
        $viewData = SuperAdminPageController::boot(
            'Super Admin Dashboard',
            'System-wide control center',
            [],
            'dashboard'
        );

        $controller = new self($viewData['conn']);
        $stats = $controller->model->getDashboardStats();

        return array_merge($viewData, [
            'systemStats' => $stats,
            'stats' => array_merge($viewData['stats'], [
                'pending_requests' => $stats['pending_requests'],
                'pending_appointments' => $stats['pending_appointments'],
            ]),
            'recentActivity' => $controller->model->getRecentActivity(),
            'createUserNotice' => $_SESSION['superadmin_create_user_notice'] ?? null,
            'createUserErrors' => $_SESSION['superadmin_create_user_errors'] ?? [],
            'createUserOld' => $_SESSION['superadmin_create_user_old'] ?? [],
        ]);
    }

    public static function users(): array
    {
        $viewData = SuperAdminPageController::boot(
            'Users and Admins',
            'Manage administrator access',
            [],
            'users'
        );

        $controller = new self($viewData['conn']);
        $notice = null;
        $errors = [];
        $actorId = (int) ($_SESSION['user_id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $result = ['success' => false, 'errors' => ['Invalid action.']];

            if ($action === 'create_admin') {
                $result = $controller->model->createAdmin($_POST, $actorId);
                $notice = $result['success'] ? 'Admin account created.' : null;
            } elseif ($action === 'update_admin') {
                $result = $controller->model->updateAdmin((int) ($_POST['admin_id'] ?? 0), $_POST, $actorId);
                $notice = $result['success'] ? 'Admin account updated.' : null;
            } elseif ($action === 'delete_admin') {
                $result = $controller->model->deleteAdmin((int) ($_POST['admin_id'] ?? 0), $actorId);
                $notice = $result['success'] ? 'Admin account deleted.' : null;
            }

            if (!$result['success']) {
                $errors = $result['errors'];
            }
        }

        return array_merge($viewData, [
            'users' => $controller->model->getAllUsers(),
            'admins' => $controller->model->getAdmins(),
            'notice' => $notice,
            'errors' => $errors,
        ]);
    }

    public static function requests(): array
    {
        $viewData = SuperAdminPageController::boot(
            'System Monitoring',
            'Read-only request monitoring',
            [],
            'requests'
        );

        $controller = new self($viewData['conn']);
        $notice = null;
        $errors = [];
        $actorId = (int) ($_SESSION['user_id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_request_status') {
            $success = $controller->model->updateRequestStatus(
                (int) ($_POST['request_id'] ?? 0),
                $_POST['type'] ?? 'regular',
                $_POST['status'] ?? 'pending',
                $actorId
            );

            $notice = $success ? 'Request status overridden.' : null;
            $errors = $success ? [] : ['Unable to update request status.'];
        }

        $systemStats = $controller->model->getDashboardStats();
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'type' => $_GET['type'] ?? '',
        ];

        return array_merge($viewData, [
            'requests' => $controller->model->getAllRequests($filters),
            'filters' => $filters,
            'notice' => $notice,
            'errors' => $errors,
            'stats' => array_merge($viewData['stats'], [
                'pending_requests' => $systemStats['pending_requests'],
                'pending_appointments' => $systemStats['pending_appointments'],
            ]),
        ]);
    }

    public static function storeUser(): void
    {
        SuperAdminPageController::boot('Create User', 'Create User');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            self::redirectToDashboard();
        }

        $controller = new self($GLOBALS['conn']);
        $actorId = (int) ($_SESSION['user_id'] ?? 0);
        $result = $controller->model->createUser($_POST, $actorId);

        unset(
            $_SESSION['superadmin_create_user_notice'],
            $_SESSION['superadmin_create_user_errors'],
            $_SESSION['superadmin_create_user_old']
        );

        if ($result['success']) {
            $_SESSION['superadmin_create_user_notice'] = 'User account created successfully.';
        } else {
            $_SESSION['superadmin_create_user_errors'] = $result['errors'];
            $_SESSION['superadmin_create_user_old'] = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => trim($_POST['role'] ?? ''),
            ];
        }

        self::redirectToDashboard();
    }

    public static function clearDashboardFlash(): void
    {
        unset(
            $_SESSION['superadmin_create_user_notice'],
            $_SESSION['superadmin_create_user_errors'],
            $_SESSION['superadmin_create_user_old']
        );
    }

    private static function redirectToDashboard(): void
    {
        header('Location: ' . self::DASHBOARD_PATH);
        exit();
    }
}
