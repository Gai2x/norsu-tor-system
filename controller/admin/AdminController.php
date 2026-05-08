<?php
require_once __DIR__ . '/../../database/connection.php';

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
}
