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

    public static function ajaxSearchStudents(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $viewData = SuperAdminPageController::boot('Ajax Students', 'Ajax Students');
        $controller = new self($viewData['conn']);

        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'course' => trim($_GET['course'] ?? ''),
            'year_level' => trim($_GET['year_level'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'request_status' => trim($_GET['request_status'] ?? ''),
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $pageSize = (int) ($_GET['pageSize'] ?? 12);

        $totalStudents = $controller->model->countStudents($filters);
        $students = $controller->model->getStudents($filters, $page, $pageSize);

        echo json_encode([
            'items' => $students,
            'total' => $totalStudents,
            'students' => $students,
            'pagination' => [
                'currentPage' => $page,
                'pageSize' => $pageSize,
                'totalItems' => $totalStudents,
                'totalPages' => max(1, (int) ceil($totalStudents / $pageSize)),
            ],
            'filters' => $filters,
        ]);
        exit;
    }

    public static function ajaxCreateAdmin(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $viewData = SuperAdminPageController::boot('Ajax Create Admin', 'Ajax Create Admin');
        $controller = new self($viewData['conn']);
        $actorId = (int) ($_SESSION['user_id'] ?? 0);

        $result = $controller->model->createAdmin($_POST, $actorId);

        echo json_encode($result);
        exit;
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
            'Student Accounts',
            'Review student accounts and filters',
            [],
            'users'
        );

        $controller = new self($viewData['conn']);
        $notice = null;
        $errors = [];
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'course' => trim($_GET['course'] ?? ''),
            'year_level' => trim($_GET['year_level'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $pageSize = 12;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_student') {
            $result = $controller->model->updateStudent((int) ($_POST['student_record_id'] ?? 0), $_POST, (int) ($_SESSION['user_id'] ?? 0));
            if ($result['success']) {
                $notice = 'Student account updated successfully.';
            } else {
                $errors = $result['errors'];
                $filters['editStudentId'] = (int) ($_POST['student_record_id'] ?? 0);
                $filters['editOld'] = $_POST;
            }
        }

        $totalStudents = $controller->model->countStudents($filters);
        $students = $controller->model->getStudents($filters, $page, $pageSize);

        return array_merge($viewData, [
            'students' => $students,
            'filters' => $filters,
            'courses' => $controller->model->getStudentCourses(),
            'yearLevels' => $controller->model->getStudentYearLevels(),
            'statuses' => $controller->model->getStudentStatuses(),
            'pagination' => [
                'currentPage' => $page,
                'pageSize' => $pageSize,
                'totalItems' => $totalStudents,
                'totalPages' => max(1, (int) ceil($totalStudents / $pageSize)),
            ],
            'notice' => $notice,
            'errors' => $errors,
        ]);
    }

    public static function admins(): array
    {
        $viewData = SuperAdminPageController::boot(
            'Admin Accounts',
            'Manage administrator profiles safely',
            [],
            'admins'
        );

        $controller = new self($viewData['conn']);
        $notice = null;
        $errors = [];
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_admin') {
            $result = $controller->model->updateAdmin((int) ($_POST['admin_id'] ?? 0), $_POST, (int) ($_SESSION['user_id'] ?? 0));
            if ($result['success']) {
                $notice = 'Admin account updated successfully.';
            } else {
                $errors = $result['errors'];
                $filters['editAdminId'] = (int) ($_POST['admin_id'] ?? 0);
                $filters['editOld'] = $_POST;
            }
        }

        return array_merge($viewData, [
            'admins' => $controller->model->getAdmins($filters),
            'filters' => $filters,
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

    public static function ajaxSearchAppointments(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $viewData = SuperAdminPageController::boot('Ajax Appointments', 'Ajax Appointments');
        $controller = new self($viewData['conn']);

        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'appointment_type' => trim($_GET['appointment_type'] ?? ''),
            'course' => trim($_GET['course'] ?? ''),
            'year_level' => trim($_GET['year_level'] ?? ''),
            'date' => trim($_GET['date'] ?? ''),
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $pageSize = (int) ($_GET['pageSize'] ?? 12);

        $totalAppointments = $controller->model->countAppointments($filters);
        $appointments = $controller->model->getAppointments($filters, $page, $pageSize);

        echo json_encode([
            'items' => $appointments,
            'total' => $totalAppointments,
            'pagination' => [
                'currentPage' => $page,
                'pageSize' => $pageSize,
                'totalItems' => $totalAppointments,
                'totalPages' => max(1, (int) ceil($totalAppointments / $pageSize)),
            ],
        ]);
        exit;
    }

    public static function ajaxSearchRequests(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $viewData = SuperAdminPageController::boot('Ajax Requests', 'Ajax Requests');
        $controller = new self($viewData['conn']);

        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'service_type' => trim($_GET['service_type'] ?? ''),
            'course' => trim($_GET['course'] ?? ''),
            'year_level' => trim($_GET['year_level'] ?? ''),
            'date' => trim($_GET['date'] ?? ''),
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $pageSize = (int) ($_GET['pageSize'] ?? 12);

        $totalRequests = $controller->model->countRequests($filters);
        $requests = $controller->model->getRequests($filters, $page, $pageSize);

        echo json_encode([
            'items' => $requests,
            'total' => $totalRequests,
            'pagination' => [
                'currentPage' => $page,
                'pageSize' => $pageSize,
                'totalItems' => $totalRequests,
                'totalPages' => max(1, (int) ceil($totalRequests / $pageSize)),
            ],
        ]);
        exit;
    }

    private static function redirectToDashboard(): void
    {
        header('Location: ' . self::DASHBOARD_PATH);
        exit();
    }
}
