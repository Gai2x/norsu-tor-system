<?php

require_once __DIR__ . '/../../database/Connection.php';
require_once __DIR__ . '/../../model/UserRoleModel.php';
require_once __DIR__ . '/../../controller/admin/AdminPageController.php';
require_once __DIR__ . '/../../controller/admin/AdminController.php';

class AdminProfilePageController
{
    public static function load()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $viewData = AdminPageController::boot('My Profile - NORSU Admin', 'Administrator Profile', [], 'profile');

        if (!isset($GLOBALS['conn']) || !$GLOBALS['conn']) {
            die('Database connection unavailable.');
        }

        $conn = $GLOBALS['conn'];
        $adminId = (int) ($_SESSION['user_id'] ?? 0);
        $errors = [];
        $notice = '';

        if ($adminId <= 0) {
            header('Location: ../Login.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'update_profile') {
                $result = AdminController::updateAdminProfile($conn, $adminId, $_POST, $_FILES);
                if ($result['success']) {
                    $_SESSION['name'] = trim($_POST['name'] ?? $_SESSION['name']);
                    header('Location: profile.php?updated=1');
                    exit();
                }

                $errors = $result['errors'];
            }

            if ($action === 'change_password') {
                $result = AdminController::changeAdminPassword($conn, $adminId, $_POST);
                if ($result['success']) {
                    header('Location: profile.php?password_updated=1');
                    exit();
                }

                $errors = $result['errors'];
            }
        }

        $stmt = $conn->prepare("SELECT id, student_id, name, email, phone_number, profile_image, role, created_at FROM students WHERE id = ? AND role IN ('admin', 'super_admin') LIMIT 1");
        $stmt->bind_param('i', $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        if (!$admin) {
            session_unset();
            session_destroy();
            header('Location: ../Login.php');
            exit();
        }

        $fullName = trim((string) ($admin['name'] ?? ''));
        $firstName = explode(' ', $fullName)[0] ?? '';
        $profileInitial = strtoupper(substr($firstName !== '' ? $firstName : 'A', 0, 1));

        return array_merge($viewData, [
            'admin' => $admin,
            'errors' => $errors,
            'notice' => $notice,
            'profileInitial' => $profileInitial,
            'fullName' => $fullName,
            'displayRole' => ucfirst(str_replace('_', ' ', $admin['role'] ?? 'admin')),
            'displayCreatedAt' => !empty($admin['created_at']) ? date('F j, Y', strtotime($admin['created_at'])) : 'Unknown',
            'displayPhone' => !empty($admin['phone_number']) ? $admin['phone_number'] : 'Not provided',
            'displayAdminId' => !empty($admin['student_id']) ? $admin['student_id'] : 'N/A',
            'displayEmail' => !empty($admin['email']) ? $admin['email'] : 'Not provided',
            'profileImageUrl' => !empty($admin['profile_image']) ? '../../uploads/' . $admin['profile_image'] : null,
            'updated' => isset($_GET['updated']),
            'passwordUpdated' => isset($_GET['password_updated']),
        ]);
    }
}
