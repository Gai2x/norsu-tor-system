<?php

require_once __DIR__ . '/../UserRoleModel.php';

class SuperAdminModel
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->ensureActionLogsTable();
    }

    public function getDashboardStats(): array
    {
        return [
            'total_users' => $this->countRows('students'),
            'total_admins' => $this->countRows('students', "role = 'admin'"),
            'total_students' => $this->countRows('students', "role = 'student'"),
            'total_requests' => $this->countRows('requests') + $this->countRows('one_time_requests'),
            'pending_requests' => $this->countRows('requests', "status = 'pending'") + $this->countRows('one_time_requests', "status = 'pending'"),
            'total_appointments' => $this->countRows('appointments'),
            'pending_appointments' => $this->countRows('appointments', "status = 'pending'"),
        ];
    }

    public function getRecentActivity(int $limit = 8): array
    {
        $stmt = $this->conn->prepare("
            SELECT al.*, s.name AS actor_name
            FROM admin_action_logs al
            LEFT JOIN students s ON al.actor_id = s.id
            ORDER BY al.created_at DESC
            LIMIT ?
        ");
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    public function getAllUsers(): array
    {
        $result = $this->conn->query("
            SELECT id, student_id, name, course, email, role, created_at
            FROM students
            ORDER BY FIELD(role, 'super_admin', 'admin', 'student'), id DESC
        ");

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAdmins(array $filters = []): array
    {
        $query = "SELECT id, student_id, name, course, email, role, created_at FROM students WHERE role = ?";
        $types = 's';
        $params = [UserRoleModel::ROLE_ADMIN];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $query .= " AND (LOWER(name) LIKE ? OR LOWER(email) LIKE ? OR LOWER(student_id) LIKE ?)";
            $types .= 'sss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        $query .= " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $this->bindParams($stmt, $types, $params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    public function getStudents(array $filters = [], int $page = 1, int $pageSize = 15): array
    {
        $offset = max(0, ($page - 1) * $pageSize);
        $baseSql = "FROM students s WHERE s.role = ?";
        $types = 's';
        $params = [UserRoleModel::ROLE_USER];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $baseSql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.course) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['course'])) {
            $baseSql .= " AND s.course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $baseSql .= " AND LEFT(s.student_id, 4) = ? AND s.student_id REGEXP '^[0-9]{4}-[0-9]{5}$'";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'missing_id') {
                $baseSql .= " AND s.student_id = ''";
            } else {
                $baseSql .= " AND s.student_id != ''";
            }
        }

        if (!empty($filters['request_status'])) {
            $baseSql .= " AND EXISTS (SELECT 1 FROM requests r WHERE r.user_id = s.id AND r.status = ?)";
            $types .= 's';
            $params[] = $filters['request_status'];
        }

        $sql = "SELECT s.id, s.student_id, s.name, s.course, s.email, s.created_at {$baseSql} ORDER BY s.created_at DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $pageSize;
        $params[] = $offset;

        $stmt = $this->conn->prepare($sql);
        $this->bindParams($stmt, $types, $params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    public function countStudents(array $filters = []): int
    {
        $baseSql = "FROM students s WHERE s.role = ?";
        $types = 's';
        $params = [UserRoleModel::ROLE_USER];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $baseSql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.course) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        if (!empty($filters['course'])) {
            $baseSql .= " AND s.course = ?";
            $types .= 's';
            $params[] = $filters['course'];
        }

        if (!empty($filters['year_level'])) {
            $baseSql .= " AND LEFT(s.student_id, 4) = ? AND s.student_id REGEXP '^[0-9]{4}-[0-9]{5}$'";
            $types .= 's';
            $params[] = $filters['year_level'];
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'missing_id') {
                $baseSql .= " AND s.student_id = ''";
            } else {
                $baseSql .= " AND s.student_id != ''";
            }
        }

        if (!empty($filters['request_status'])) {
            $baseSql .= " AND EXISTS (SELECT 1 FROM requests r WHERE r.user_id = s.id AND r.status = ?)";
            $types .= 's';
            $params[] = $filters['request_status'];
        }

        $sql = "SELECT COUNT(*) AS total {$baseSql}";
        $stmt = $this->conn->prepare($sql);
        $this->bindParams($stmt, $types, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result ? (int) ($result->fetch_assoc()['total'] ?? 0) : 0;
        $stmt->close();

        return $count;
    }

    public function getStudentCourses(): array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT course FROM students WHERE role = ? AND course != '' ORDER BY course ASC");
        $role = UserRoleModel::ROLE_USER;
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_map(fn($row) => $row['course'], $rows);
    }

    public function getStudentYearLevels(): array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT LEFT(student_id, 4) AS year_level FROM students WHERE role = ? AND student_id REGEXP '^[0-9]{4}-[0-9]{5}$' ORDER BY year_level DESC");
        $role = UserRoleModel::ROLE_USER;
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_values(array_map(fn($row) => $row['year_level'], $rows));
    }

    public function getStudentStatuses(): array
    {
        return [
            'has_id' => 'Has student ID',
            'missing_id' => 'Missing student ID',
        ];
    }

    public function updateStudent(int $studentId, array $input, int $actorId): array
    {
        if (!$this->isStudent($studentId)) {
            return ['success' => false, 'errors' => ['Student account was not found.']];
        }

        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $studentCode = trim($input['student_id'] ?? '');
        $course = trim($input['course'] ?? '');
        $password = $input['password'] ?? '';
        $errors = [];

        if ($name === '') {
            $errors[] = 'Full name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if ($studentCode === '') {
            $errors[] = 'Student ID is required.';
        } elseif (!preg_match('/^[0-9]{4}-[0-9]{5}$/', $studentCode)) {
            $errors[] = 'Student ID must follow 2021-12345 format.';
        }

        if ($course === '') {
            $errors[] = 'Course is required.';
        }

        if ($password !== '' && strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($this->emailOrStudentIdExists($email, $studentCode, $studentId)) {
            $errors[] = 'Email or Student ID already exists.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE students SET student_id = ?, name = ?, course = ?, email = ?, password = ? WHERE id = ? AND role = 'student'");
            $stmt->bind_param('sssssi', $studentCode, $name, $course, $email, $hashedPassword, $studentId);
        } else {
            $stmt = $this->conn->prepare("UPDATE students SET student_id = ?, name = ?, course = ?, email = ? WHERE id = ? AND role = 'student'");
            $stmt->bind_param('ssssi', $studentCode, $name, $course, $email, $studentId);
        }

        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($actorId, 'update_student', 'students', $studentId, "Updated student {$email}");
        }

        return ['success' => $success, 'errors' => $success ? [] : ['Unable to update student account.']];
    }

    private function isStudent(int $id): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM students WHERE id = ? AND role = 'student'");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $exists;
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

    public function createAdmin(array $input, int $actorId): array
    {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $adminCode = trim($input['student_id'] ?? '');
        $course = trim($input['course'] ?? 'Administration');
        $password = $input['password'] ?? '';

        $errors = $this->validateAdminInput($name, $email, $adminCode, $course, $password);
        if ($this->emailOrStudentIdExists($email, $adminCode)) {
            $errors[] = 'Email or Admin ID already exists.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = UserRoleModel::ROLE_ADMIN;
        $stmt = $this->conn->prepare("
            INSERT INTO students (student_id, name, course, email, password, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssss', $adminCode, $name, $course, $email, $hashedPassword, $role);
        $success = $stmt->execute();
        $newId = (int) $this->conn->insert_id;
        $stmt->close();

        if ($success) {
            $this->logAction($actorId, 'create_admin', 'students', $newId, "Created admin {$email}");
        }

        return ['success' => $success, 'errors' => $success ? [] : ['Unable to create admin.']];
    }

    public function createUser(array $input, int $actorId): array
    {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $role = $this->normalizeRole($input['role'] ?? '');

        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($role === null) {
            $errors[] = 'Please select a valid role.';
        }

        if ($this->emailExists($email)) {
            $errors[] = 'Email already exists.';
        }

        if (!empty($errors) || $role === null) {
            return ['success' => false, 'errors' => $errors];
        }

        $studentId = $this->generateUniqueStudentId($role);
        $course = $role === UserRoleModel::ROLE_USER ? 'Unassigned' : 'Administration';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            INSERT INTO students (student_id, name, course, email, password, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssss', $studentId, $name, $course, $email, $hashedPassword, $role);
        $success = $stmt->execute();
        $newId = (int) $this->conn->insert_id;
        $stmt->close();

        if ($success) {
            $this->logAction($actorId, 'create_user', 'students', $newId, "Created {$role} account {$email}");
        }

        return ['success' => $success, 'errors' => $success ? [] : ['Unable to create user.']];
    }

    public function updateAdmin(int $adminId, array $input, int $actorId): array
    {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $adminCode = trim($input['student_id'] ?? '');
        $course = trim($input['course'] ?? 'Administration');
        $password = $input['password'] ?? '';

        if (!$this->isAdmin($adminId)) {
            return ['success' => false, 'errors' => ['Admin account was not found.']];
        }

        $errors = $this->validateAdminInput($name, $email, $adminCode, $course, $password, false);
        if ($this->emailOrStudentIdExists($email, $adminCode, $adminId)) {
            $errors[] = 'Email or Admin ID already exists.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("
                UPDATE students
                SET student_id = ?, name = ?, course = ?, email = ?, password = ?
                WHERE id = ? AND role = 'admin'
            ");
            $stmt->bind_param('sssssi', $adminCode, $name, $course, $email, $hashedPassword, $adminId);
        } else {
            $stmt = $this->conn->prepare("
                UPDATE students
                SET student_id = ?, name = ?, course = ?, email = ?
                WHERE id = ? AND role = 'admin'
            ");
            $stmt->bind_param('ssssi', $adminCode, $name, $course, $email, $adminId);
        }

        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($actorId, 'update_admin', 'students', $adminId, "Updated admin {$email}");
        }

        return ['success' => $success, 'errors' => $success ? [] : ['Unable to update admin.']];
    }

    public function deleteAdmin(int $adminId, int $actorId): array
    {
        if (!$this->isAdmin($adminId)) {
            return ['success' => false, 'errors' => ['Admin account was not found.']];
        }

        $stmt = $this->conn->prepare("DELETE FROM students WHERE id = ? AND role = 'admin'");
        $stmt->bind_param('i', $adminId);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($actorId, 'delete_admin', 'students', $adminId, 'Deleted admin account');
        }

        return ['success' => $success, 'errors' => $success ? [] : ['Unable to delete admin.']];
    }

    public function getAllRequests(array $filters = []): array
    {
        $requests = [];
        $search = strtolower(trim($filters['search'] ?? ''));
        $statusFilter = strtolower(trim($filters['status'] ?? ''));
        $typeFilter = strtolower(trim($filters['type'] ?? ''));

        $regular = $this->conn->query("
            SELECT
                r.id,
                COALESCE(s.name, 'N/A') AS name,
                COALESCE(s.student_id, 'N/A') AS student_id,
                COALESCE(s.email, 'N/A') AS email,
                COALESCE(s.course, 'N/A') AS course,
                r.service_type,
                r.status,
                r.notes,
                r.created_at,
                'regular' AS type
            FROM requests r
            LEFT JOIN students s ON r.user_id = s.id
        ");
        if ($regular) {
            $requests = array_merge($requests, $regular->fetch_all(MYSQLI_ASSOC));
        }

        $oneTime = $this->conn->query("
            SELECT
                id,
                COALESCE(fullname, 'N/A') AS name,
                COALESCE(student_id, 'N/A') AS student_id,
                COALESCE(email, 'N/A') AS email,
                COALESCE(contact, 'N/A') AS contact,
                service_type,
                status,
                notes,
                created_at,
                'one_time' AS type
            FROM one_time_requests
        ");
        if ($oneTime) {
            $requests = array_merge($requests, $oneTime->fetch_all(MYSQLI_ASSOC));
        }

        $requests = array_values(array_filter($requests, function (array $request) use ($search, $statusFilter, $typeFilter): bool {
            if ($statusFilter !== '' && strtolower($request['status'] ?? '') !== $statusFilter) {
                return false;
            }

            if ($typeFilter !== '' && strtolower($request['type'] ?? '') !== $typeFilter) {
                return false;
            }

            if ($search === '') {
                return true;
            }

            $haystack = strtolower(implode(' ', [
                $request['id'] ?? '',
                $request['name'] ?? '',
                $request['student_id'] ?? '',
                $request['email'] ?? '',
                $request['service_type'] ?? '',
                $request['status'] ?? '',
            ]));

            return strpos($haystack, $search) !== false;
        }));

        usort($requests, function (array $a, array $b): int {
            return strtotime($b['created_at'] ?? '1970-01-01') <=> strtotime($a['created_at'] ?? '1970-01-01');
        });

        return $requests;
    }

    public function updateRequestStatus(int $requestId, string $type, string $status, int $actorId): bool
    {
        if (!in_array($status, ['approved', 'rejected', 'pending'], true)) {
            return false;
        }

        $table = $type === 'one_time' ? 'one_time_requests' : 'requests';
        $stmt = $this->conn->prepare("UPDATE {$table} SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $requestId);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($actorId, 'override_request_status', $table, $requestId, "Set status to {$status}");
        }

        return $success;
    }

    private function validateAdminInput(
        string $name,
        string $email,
        string $adminCode,
        string $course,
        string $password,
        bool $passwordRequired = true
    ): array {
        $errors = [];

        if ($name === '') {
            $errors[] = 'Full name is required.';
        }

        if ($adminCode === '') {
            $errors[] = 'Admin ID is required.';
        }

        if ($course === '') {
            $errors[] = 'Department is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if ($passwordRequired || $password !== '') {
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            }
        }

        return $errors;
    }

    private function normalizeRole(string $role): ?string
    {
        $normalizedRole = strtolower(trim($role));

        if ($normalizedRole === 'superadmin') {
            return UserRoleModel::ROLE_SUPER_ADMIN;
        }

        if (in_array($normalizedRole, UserRoleModel::all(), true)) {
            return $normalizedRole;
        }

        return null;
    }

    private function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    private function generateUniqueStudentId(string $role): string
    {
        $prefixMap = [
            UserRoleModel::ROLE_USER => 'STU',
            UserRoleModel::ROLE_ADMIN => 'ADM',
            UserRoleModel::ROLE_SUPER_ADMIN => 'SUP',
        ];

        do {
            $studentId = sprintf(
                '%s-%s-%04d',
                $prefixMap[$role] ?? 'USR',
                date('YmdHis'),
                random_int(1000, 9999)
            );
        } while ($this->emailOrStudentIdExists('', $studentId));

        return $studentId;
    }

    private function emailOrStudentIdExists(string $email, string $studentId, ?int $ignoreId = null): bool
    {
        if ($ignoreId) {
            $stmt = $this->conn->prepare("SELECT id FROM students WHERE (email = ? OR student_id = ?) AND id != ?");
            $stmt->bind_param('ssi', $email, $studentId, $ignoreId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM students WHERE email = ? OR student_id = ?");
            $stmt->bind_param('ss', $email, $studentId);
        }

        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    private function isAdmin(int $id): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM students WHERE id = ? AND role = 'admin'");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    private function countRows(string $table, string $where = ''): int
    {
        $allowedTables = ['students', 'requests', 'one_time_requests', 'appointments'];
        if (!in_array($table, $allowedTables, true)) {
            return 0;
        }

        $sql = "SELECT COUNT(*) AS total FROM {$table}";
        if ($where !== '') {
            $sql .= " WHERE {$where}";
        }

        $result = $this->conn->query($sql);
        return $result ? (int) ($result->fetch_assoc()['total'] ?? 0) : 0;
    }

    private function logAction(int $actorId, string $action, string $targetType, ?int $targetId, string $details): void
    {
        $stmt = $this->conn->prepare("
            INSERT INTO admin_action_logs (actor_id, action, target_type, target_id, details)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('issis', $actorId, $action, $targetType, $targetId, $details);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Get appointments with filtering and pagination
     */
    public function getAppointments(array $filters = [], int $page = 1, int $pageSize = 15): array
    {
        $offset = max(0, ($page - 1) * $pageSize);
        $sql = "SELECT a.id, a.user_id, a.appointment_type, a.appointment_date, a.appointment_time, 
                       a.service_type, a.purpose, a.status, a.admin_notes,
                       s.name, s.student_id, s.course, s.email
                FROM appointments a
                JOIN students s ON a.user_id = s.id
                WHERE 1=1";
        
        $types = '';
        $params = [];
        $search = strtolower(trim($filters['search'] ?? ''));

        if ($search !== '') {
            $sql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(a.purpose) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
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
            $sql .= " AND (LOWER(s.name) LIKE ? OR LOWER(s.student_id) LIKE ? OR LOWER(s.email) LIKE ? OR LOWER(a.purpose) LIKE ?)";
            $types .= 'ssss';
            $searchValue = "%{$search}%";
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
     * Get requests with filtering and pagination
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
     * Get distinct appointment types
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
     * Get request categories
     */
    public function getRequestCategories(): array
    {
        return [
            'Document Request',
            'Consultation',
            'Enrollment Concern',
            'Grade Concern',
            'Other'
        ];
    }

    private function ensureActionLogsTable(): void
    {
        $this->conn->query("
            CREATE TABLE IF NOT EXISTS admin_action_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                actor_id INT NULL,
                action VARCHAR(100) NOT NULL,
                target_type VARCHAR(100) NULL,
                target_id INT NULL,
                details TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_actor_id (actor_id),
                INDEX idx_created_at (created_at)
            )
        ");
    }
}

