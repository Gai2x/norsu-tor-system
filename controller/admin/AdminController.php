<?php
require_once __DIR__ . '/../../database/connection.php';
require_once __DIR__ . '/../../model/UserRoleModel.php';

class AdminController {

    /*
    |----------------------------------------------------
    | UPDATE REQUEST STATUS (APPROVE / REJECT)
    |----------------------------------------------------
    */
    public static function updateRequestStatus($conn){

        if (!isset($_GET['approve']) && !isset($_GET['reject'])) {
            return;
        }

        $action = isset($_GET['approve']) ? 'approve' : 'reject';
        $id = intval($_GET[$action]);
        $type = $_GET['type'] ?? 'student';

        $status = ($action === 'approve') ? 'approved' : 'rejected';

        // Choose table safely
        $table = ($type === 'one_time') ? 'one_time_requests' : 'requests';

        $stmt = $conn->prepare("UPDATE $table SET status=? WHERE id=?");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        header("Location: Requests.php");
        exit();
    }
    /*
    |----------------------------------------------------
    | DASHBOARD DATA (CLEAN MVC STYLE)
    |----------------------------------------------------
    */
    public static function getDashboardData($conn){

        $data = [];

        // TOTAL STUDENTS
        $result = $conn->query("SELECT COUNT(*) AS total FROM students");
        $data['totalStudents'] = $result ? $result->fetch_assoc()['total'] : 0;

        // TOTAL APPOINTMENTS
        $result = $conn->query("SELECT COUNT(*) AS total FROM appointments");
        $data['totalAppointments'] = $result ? $result->fetch_assoc()['total'] : 0;

        // APPROVED THIS MONTH
        $result = $conn->query("
            SELECT COUNT(*) AS total
            FROM requests
            WHERE status = 'approved'
            AND MONTH(updated_at) = MONTH(CURDATE())
            AND YEAR(updated_at) = YEAR(CURDATE())
        ");
        $data['approvedThisMonth'] = $result ? $result->fetch_assoc()['total'] : 0;

        // RECENT APPOINTMENTS
        $result = $conn->query("
            SELECT a.*, s.name AS student_name
            FROM appointments a
            LEFT JOIN students s ON a.user_id = s.id
            ORDER BY a.created_at DESC
            LIMIT 5
        ");
        $data['recentAppointments'] = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // RECENT REQUESTS
        $result = $conn->query("
            SELECT r.*, s.name AS student_name, s.student_id
            FROM requests r
            LEFT JOIN students s ON r.user_id = s.id
            ORDER BY r.created_at DESC
            LIMIT 5
        ");
        $data['recentRequests'] = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // RECENT STUDENTS
        $result = $conn->query("
            SELECT * FROM students
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $data['recentStudents'] = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return $data;
    }

    /**
     * AJAX: Search students with filters
     */
    public static function ajaxSearchStudents($conn)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        require_once __DIR__ . '/../../model/admin/AdminModel.php';
        $model = new AdminModel($conn);

        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'course' => trim($_GET['course'] ?? ''),
            'year_level' => trim($_GET['year_level'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $pageSize = (int) ($_GET['pageSize'] ?? 12);

        $totalStudents = $model->countStudents($filters);
        $students = $model->getStudents($filters, $page, $pageSize);

        echo json_encode([
            'items' => $students,
            'total' => $totalStudents,
            'pagination' => [
                'currentPage' => $page,
                'pageSize' => $pageSize,
                'totalItems' => $totalStudents,
                'totalPages' => max(1, (int) ceil($totalStudents / $pageSize)),
            ],
        ]);
        exit;
    }

    /**
     * AJAX: Search requests with filters
     */
    public static function ajaxSearchRequests($conn)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        require_once __DIR__ . '/../../model/admin/AdminModel.php';
        $model = new AdminModel($conn);

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

        $totalRequests = $model->countRequests($filters);
        $requests = $model->getRequests($filters, $page, $pageSize);

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

    /**
     * AJAX: Search appointments with filters
     */
    public static function ajaxSearchAppointments($conn)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        require_once __DIR__ . '/../../model/admin/AdminModel.php';
        $model = new AdminModel($conn);

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

        $totalAppointments = $model->countAppointments($filters);
        $appointments = $model->getAppointments($filters, $page, $pageSize);

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

    public static function ajaxAdminProfile($conn)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        UserRoleModel::requireAnyRole([
            UserRoleModel::ROLE_ADMIN,
            UserRoleModel::ROLE_SUPER_ADMIN,
        ], '../Login.php');

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'errors' => ['Method not allowed.']]);
            exit;
        }

        $action = trim($_POST['action'] ?? '');
        $adminId = (int) ($_SESSION['user_id'] ?? 0);

        if ($adminId <= 0) {
            http_response_code(403);
            echo json_encode(['success' => false, 'errors' => ['Unauthorized access.']]);
            exit;
        }

        if ($action === 'update_profile') {
            $result = self::updateAdminProfile($conn, $adminId, $_POST, $_FILES);
            echo json_encode($result);
            exit;
        }

        if ($action === 'change_password') {
            $result = self::changeAdminPassword($conn, $adminId, $_POST);
            echo json_encode($result);
            exit;
        }

        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => ['Invalid action specified.']]);
        exit;
    }

    public static function updateAdminProfile($conn, int $adminId, array $input, array $files): array
    {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phoneNumber = trim($input['phone_number'] ?? '');
        $profileImage = null;
        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }

        if ($phoneNumber !== '' && !preg_match('/^(09\d{9}|\+639\d{9})$/', $phoneNumber)) {
            $errors[] = 'Phone number must use 09######### or +639######### format.';
        }

        $currentImage = null;
        $stmt = $conn->prepare("SELECT profile_image FROM students WHERE id = ? AND role IN ('admin', 'super_admin') LIMIT 1");
        $stmt->bind_param('i', $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if ($row) {
            $currentImage = $row['profile_image'];
        }

        if (!empty($files['profile_pic']['name'])) {
            $imageInfo = @getimagesize($files['profile_pic']['tmp_name']);
            $allowedTypes = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif'];

            if ($imageInfo === false || !isset($allowedTypes[$imageInfo[2]])) {
                $errors[] = 'Please upload a valid PNG, JPG, or GIF image.';
            } elseif ($files['profile_pic']['size'] > 2 * 1024 * 1024) {
                $errors[] = 'Image size must be 2MB or less.';
            } else {
                $extension = $allowedTypes[$imageInfo[2]];
                $fileName = time() . '_admin_' . $adminId . '.' . $extension;
                $targetDir = __DIR__ . '/../../uploads/';
                $targetFile = $targetDir . $fileName;

                if (!move_uploaded_file($files['profile_pic']['tmp_name'], $targetFile)) {
                    $errors[] = 'Unable to upload profile image. Please try again.';
                } else {
                    $profileImage = $fileName;
                }
            }
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($profileImage === null) {
            $profileImage = $currentImage;
        }

        $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, phone_number = ?, profile_image = ? WHERE id = ? AND role IN ('admin', 'super_admin')");
        $stmt->bind_param('ssssi', $name, $email, $phoneNumber, $profileImage, $adminId);
        $success = $stmt->execute();
        $stmt->close();

        if (!$success) {
            return ['success' => false, 'errors' => ['Unable to update profile at this time.']];
        }

        return ['success' => true, 'errors' => []];
    }

    public static function changeAdminPassword($conn, int $adminId, array $input): array
    {
        $currentPassword = $input['current_password'] ?? '';
        $newPassword = $input['new_password'] ?? '';
        $confirmPassword = $input['confirm_password'] ?? '';
        $errors = [];

        if ($currentPassword === '') {
            $errors[] = 'Current password is required.';
        }

        if (strlen($newPassword) < 6) {
            $errors[] = 'New password must be at least 6 characters long.';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New password and confirmation do not match.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $stmt = $conn->prepare("SELECT password FROM students WHERE id = ? AND role IN ('admin', 'super_admin') LIMIT 1");
        $stmt->bind_param('i', $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$row || !password_verify($currentPassword, $row['password'])) {
            return ['success' => false, 'errors' => ['Current password is incorrect.']];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE students SET password = ? WHERE id = ? AND role IN ('admin', 'super_admin')");
        $stmt->bind_param('si', $hashedPassword, $adminId);
        $success = $stmt->execute();
        $stmt->close();

        if (!$success) {
            return ['success' => false, 'errors' => ['Unable to update password at this time.']];
        }

        return ['success' => true, 'errors' => []];
    }
}
