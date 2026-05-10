<?php

class AdminModel
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Get students with filtering and pagination for admin
     */
    public function getStudents(array $filters = [], int $page = 1, int $pageSize = 15): array
    {
        $offset = max(0, ($page - 1) * $pageSize);
        $sql = "SELECT id, student_id, name, course, email, created_at
                FROM students
                WHERE role = 'student'";

        $types = '';
        $params = [];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $sql .= " AND (LOWER(name) LIKE ? OR LOWER(student_id) LIKE ? OR LOWER(email) LIKE ? OR LOWER(course) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['course'])) {
            $sql .= " AND course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $sql .= " AND LEFT(student_id, 4) = ? AND student_id REGEXP '^[0-9]{4}-[0-9]{5}$'";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'missing_id') {
                $sql .= " AND (student_id IS NULL OR student_id = '')";
            } elseif ($filters['status'] === 'has_id') {
                $sql .= " AND student_id IS NOT NULL AND student_id != ''";
            }
        }

        $sql .= " ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $pageSize;
        $params[] = $offset;

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $this->bindParams($stmt, $types, $params);
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    /**
     * Count students with filtering
     */
    public function countStudents(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) AS total FROM students WHERE role = 'student'";

        $types = '';
        $params = [];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $sql .= " AND (LOWER(name) LIKE ? OR LOWER(student_id) LIKE ? OR LOWER(email) LIKE ? OR LOWER(course) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['course'])) {
            $sql .= " AND course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $sql .= " AND LEFT(student_id, 4) = ? AND student_id REGEXP '^[0-9]{4}-[0-9]{5}$'";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'missing_id') {
                $sql .= " AND (student_id IS NULL OR student_id = '')";
            } elseif ($filters['status'] === 'has_id') {
                $sql .= " AND student_id IS NOT NULL AND student_id != ''";
            }
        }

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $this->bindParams($stmt, $types, $params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result ? (int) ($result->fetch_assoc()['total'] ?? 0) : 0;
        $stmt->close();

        return $count;
    }

    /**
     * Get student account status filter options.
     */
    public function getStudentStatuses(): array
    {
        return [
            'has_id' => 'Complete Profile',
            'missing_id' => 'Missing Student ID',
        ];
    }

    /**
     * Get requests with filtering and pagination for admin
     */
    public function getRequests(array $filters = [], int $page = 1, int $pageSize = 15): array
    {
        $offset = max(0, ($page - 1) * $pageSize);
        $sql = "SELECT r.id, r.user_id, r.service_type, r.notes, r.year_level, 
                       r.status, r.created_at,
                       s.name, s.student_id, s.course, s.email
                FROM requests r
                JOIN students s ON r.user_id = s.id
                WHERE 1=1";
        
        $types = '';
        $params = [];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $sql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(r.notes) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND r.status = ?";
            $types .= 's';
            $params[] = $filters['status'];
        }

        if (!empty($filters['service_type'])) {
            $sql .= " AND r.service_type = ?";
            $types .= 's';
            $params[] = $filters['service_type'];
        }

        if (!empty($filters['course'])) {
            $sql .= " AND s.course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $sql .= " AND r.year_level = ?";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(r.created_at) = ?";
            $types .= 's';
            $params[] = $filters['date'];
        }

        $sql .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $pageSize;
        $params[] = $offset;

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $this->bindParams($stmt, $types, $params);
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    /**
     * Count requests with filtering
     */
    public function countRequests(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) AS total FROM requests r JOIN students s ON r.user_id = s.id WHERE 1=1";
        
        $types = '';
        $params = [];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $sql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(r.notes) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND r.status = ?";
            $types .= 's';
            $params[] = $filters['status'];
        }

        if (!empty($filters['service_type'])) {
            $sql .= " AND r.service_type = ?";
            $types .= 's';
            $params[] = $filters['service_type'];
        }

        if (!empty($filters['course'])) {
            $sql .= " AND s.course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $sql .= " AND r.year_level = ?";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(r.created_at) = ?";
            $types .= 's';
            $params[] = $filters['date'];
        }

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $this->bindParams($stmt, $types, $params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result ? (int) ($result->fetch_assoc()['total'] ?? 0) : 0;
        $stmt->close();

        return $count;
    }

    /**
     * Get appointments with filtering and pagination for admin
     */
    public function getAppointments(array $filters = [], int $page = 1, int $pageSize = 15): array
    {
        $offset = max(0, ($page - 1) * $pageSize);
        $sql = "SELECT a.id, a.user_id, a.appointment_type, a.appointment_date, a.appointment_time, 
                       a.service_type, a.purpose, a.status, a.admin_notes,
                       s.name, s.student_id, s.course, s.email,
                       COALESCE(sc.office, a.advisor_name, 'Unassigned') AS office
                FROM appointments a
                JOIN students s ON a.user_id = s.id
                LEFT JOIN schedules sc ON a.schedule_id = sc.id
                WHERE 1=1";
        
        $types = '';
        $params = [];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $sql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(s.course) LIKE ? OR LOWER(a.appointment_type) LIKE ? OR LOWER(a.purpose) LIKE ?)";
            $types .= 'ssssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $types .= 's';
            $params[] = $filters['status'];
        }

        if (!empty($filters['appointment_type'])) {
            $sql .= " AND a.appointment_type = ?";
            $types .= 's';
            $params[] = $filters['appointment_type'];
        }

        if (!empty($filters['course'])) {
            $sql .= " AND s.course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $sql .= " AND LEFT(s.student_id, 4) = ?";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(a.appointment_date) = ?";
            $types .= 's';
            $params[] = $filters['date'];
        }

        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $pageSize;
        $params[] = $offset;

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $this->bindParams($stmt, $types, $params);
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    /**
     * Count appointments with filtering
     */
    public function countAppointments(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) AS total FROM appointments a JOIN students s ON a.user_id = s.id WHERE 1=1";
        
        $types = '';
        $params = [];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $sql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(s.course) LIKE ? OR LOWER(a.appointment_type) LIKE ? OR LOWER(a.purpose) LIKE ?)";
            $types .= 'ssssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $types .= 's';
            $params[] = $filters['status'];
        }

        if (!empty($filters['appointment_type'])) {
            $sql .= " AND a.appointment_type = ?";
            $types .= 's';
            $params[] = $filters['appointment_type'];
        }

        if (!empty($filters['course'])) {
            $sql .= " AND s.course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $sql .= " AND LEFT(s.student_id, 4) = ?";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(a.appointment_date) = ?";
            $types .= 's';
            $params[] = $filters['date'];
        }

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $this->bindParams($stmt, $types, $params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result ? (int) ($result->fetch_assoc()['total'] ?? 0) : 0;
        $stmt->close();

        return $count;
    }

    /**
     * Get appointment types
     */
    public function getAppointmentTypes(): array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT appointment_type FROM appointments WHERE appointment_type != '' ORDER BY appointment_type ASC");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_map(fn($row) => $row['appointment_type'], $rows);
    }

    /**
     * Get service types
     */
    public function getServiceTypes(): array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT service_type FROM requests WHERE service_type != '' ORDER BY service_type ASC");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_map(fn($row) => $row['service_type'], $rows);
    }

    /**
     * Get courses for filtering
     */
    public function getCourses(): array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT course FROM students WHERE course != '' AND role = 'student' ORDER BY course ASC");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_map(fn($row) => $row['course'], $rows);
    }

    /**
     * Get year levels for filtering
     */
    public function getYearLevels(): array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT LEFT(student_id, 4) AS year_level FROM students WHERE student_id REGEXP '^[0-9]{4}-[0-9]{5}$' AND role = 'student' ORDER BY year_level DESC");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_values(array_map(fn($row) => $row['year_level'], $rows));
    }

    private function bindParams(mysqli_stmt $stmt, string $types, array $params): bool
    {
        if ($types === '' || empty($params)) {
            return true;
        }

        $bindNames = [];
        $bindNames[] = $types;

        foreach ($params as $key => $value) {
            $bindNames[] = &$params[$key];
        }

        return call_user_func_array([$stmt, 'bind_param'], $bindNames);
    }
}
