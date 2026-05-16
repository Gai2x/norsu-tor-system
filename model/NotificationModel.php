<?php

class NotificationModel
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->ensureTable();
    }

    public function getForAccount(int $userId, string $role, int $limit = 20): array
    {
        $notifications = array_merge(
            $this->getStoredNotifications($userId, $limit),
            $this->getDerivedNotifications($userId, $role)
        );

        usort($notifications, function (array $left, array $right): int {
            return strtotime($right['created_at'] ?? 'now') <=> strtotime($left['created_at'] ?? 'now');
        });

        return array_slice($notifications, 0, $limit);
    }

    public function countUnreadForAccount(int $userId, string $role): int
    {
        return count(array_filter($this->getForAccount($userId, $role), function (array $notification): bool {
            return empty($notification['is_read']);
        }));
    }

    public function markAsRead(int $userId, string $notificationId): bool
    {
        if (strpos($notificationId, 'db-') !== 0) {
            return false;
        }

        $id = (int) substr($notificationId, 3);
        if ($id <= 0) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ii', $id, $userId);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function createNotification(int $userId, string $title, string $message, string $type = 'info'): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO notifications (user_id, title, message, type, is_read) VALUES (?, ?, ?, ?, 0)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('isss', $userId, $title, $message, $type);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    private function ensureTable(): void
    {
        $this->conn->query("
            CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                type VARCHAR(50) DEFAULT 'info',
                is_read TINYINT(1) DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_id (user_id),
                INDEX idx_is_read (is_read),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    private function getStoredNotifications(int $userId, int $limit): array
    {
        $stmt = $this->conn->prepare("
            SELECT id, title, message, type, is_read, created_at
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_map(function (array $row): array {
            return [
                'id' => 'db-' . (int) $row['id'],
                'title' => $row['title'],
                'message' => $row['message'],
                'type' => $row['type'] ?: 'info',
                'is_read' => (bool) $row['is_read'],
                'created_at' => $row['created_at'],
            ];
        }, $rows);
    }

    private function getDerivedNotifications(int $userId, string $role): array
    {
        if ($role === 'student') {
            return $this->getStudentNotifications($userId);
        }

        if ($role === 'admin' || $role === 'super_admin') {
            return $this->getStaffNotifications();
        }

        return [];
    }

    private function getStudentNotifications(int $userId): array
    {
        $items = [];

        $pending = $this->fetchCount("SELECT COUNT(*) AS total FROM requests WHERE user_id = ? AND status = 'pending'", $userId);
        if ($pending > 0) {
            $items[] = $this->systemItem('student-pending-requests', 'Pending requests', "{$pending} request" . ($pending === 1 ? '' : 's') . ' awaiting review.', 'request');
        }

        $upcoming = $this->fetchCount("
            SELECT COUNT(*) AS total
            FROM appointments
            WHERE user_id = ?
              AND appointment_date >= CURDATE()
              AND LOWER(status) IN ('pending', 'approved')
        ", $userId);
        if ($upcoming > 0) {
            $items[] = $this->systemItem('student-upcoming-appointments', 'Upcoming appointments', "{$upcoming} appointment" . ($upcoming === 1 ? '' : 's') . ' on your schedule.', 'appointment');
        }

        $stmt = $this->conn->prepare("
            SELECT service_type, status, updated_at, created_at
            FROM requests
            WHERE user_id = ?
              AND status IN ('approved', 'rejected', 'cancelled')
            ORDER BY COALESCE(updated_at, created_at) DESC
            LIMIT 5
        ");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($rows as $index => $row) {
            $status = ucfirst((string) ($row['status'] ?? 'updated'));
            $items[] = $this->systemItem(
                'student-request-status-' . $index,
                "Request {$status}",
                ($row['service_type'] ?? 'Your request') . " was marked " . strtolower($status) . '.',
                'request',
                $row['updated_at'] ?? $row['created_at'] ?? null,
                true
            );
        }

        return $items;
    }

    private function getStaffNotifications(): array
    {
        $items = [];

        $regularPending = $this->fetchCount("SELECT COUNT(*) AS total FROM requests WHERE status = 'pending'");
        $oneTimePending = $this->fetchCount("SELECT COUNT(*) AS total FROM one_time_requests WHERE status = 'pending'");
        $pendingRequests = $regularPending + $oneTimePending;

        if ($pendingRequests > 0) {
            $items[] = $this->systemItem('staff-pending-requests', 'Requests need review', "{$pendingRequests} request" . ($pendingRequests === 1 ? '' : 's') . ' pending approval.', 'request');
        }

        $pendingAppointments = $this->fetchCount("SELECT COUNT(*) AS total FROM appointments WHERE LOWER(status) = 'pending'");
        if ($pendingAppointments > 0) {
            $items[] = $this->systemItem('staff-pending-appointments', 'Appointments need review', "{$pendingAppointments} appointment" . ($pendingAppointments === 1 ? '' : 's') . ' pending approval.', 'appointment');
        }

        return $items;
    }

    private function fetchCount(string $sql, ?int $userId = null): int
    {
        $stmt = $this->conn->prepare($sql);
        if ($userId !== null) {
            $stmt->bind_param('i', $userId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result ? (int) ($result->fetch_assoc()['total'] ?? 0) : 0;
        $stmt->close();

        return $count;
    }

    private function systemItem(string $id, string $title, string $message, string $type, ?string $createdAt = null, bool $isRead = false): array
    {
        return [
            'id' => 'system-' . $id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => $isRead,
            'created_at' => $createdAt ?: date('Y-m-d H:i:s'),
        ];
    }
}
