<?php

class AppointmentModel {
    private $conn;
    
    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }

    /*
    =========================
        USER FUNCTIONS
    =========================
    */

    public function getUserAppointments($user_id, $status = null) {
        $sql = "
            SELECT
                a.*,
                a.appointment_type AS service_type,
                COALESCE(sc.office, a.advisor_name, 'N/A') AS office,
                sc.id AS linked_schedule_id,
                sc.start_time AS schedule_start_time,
                sc.end_time AS schedule_end_time
            FROM appointments a
            LEFT JOIN schedules sc ON a.schedule_id = sc.id
            WHERE a.user_id = ?
        ";
        $params = [$user_id];
        $types = "i";
        
        if ($status) {
            $sql .= " AND LOWER(a.status) = ?";
            $params[] = strtolower($status);
            $types .= "s";
        }
        
        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        
        $stmt->close();
        return $appointments;
    }

    public function getUpcomingAppointments($user_id) {
        $sql = "
            SELECT
                a.*,
                a.appointment_type AS service_type,
                COALESCE(sc.office, a.advisor_name, 'N/A') AS office,
                sc.id AS linked_schedule_id,
                sc.start_time AS schedule_start_time,
                sc.end_time AS schedule_end_time
            FROM appointments a
            LEFT JOIN schedules sc ON a.schedule_id = sc.id
            WHERE a.user_id = ?
              AND LOWER(a.status) IN ('pending', 'approved')
              AND a.appointment_date >= CURDATE()
            ORDER BY a.appointment_date ASC, a.appointment_time ASC
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        
        $stmt->close();
        return $appointments;
    }

    public function getPastAppointments($user_id, $limit = 10) {
        $sql = "
            SELECT
                a.*,
                a.appointment_type AS service_type,
                COALESCE(sc.office, a.advisor_name, 'N/A') AS office
            FROM appointments a
            LEFT JOIN schedules sc ON a.schedule_id = sc.id
            WHERE a.user_id = ?
              AND (LOWER(a.status) IN ('completed', 'cancelled', 'rejected') OR a.appointment_date < CURDATE())
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
            LIMIT ?
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        
        $stmt->close();
        return $appointments;
    }

    public function getAppointmentById($appointment_id, $user_id) {
        $sql = "SELECT * FROM appointments WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $appointment_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $appointment = $result->fetch_assoc();
        $stmt->close();
        
        return $appointment;
    }

    public function bookAppointment($user_id, $data) {
        $sql = "INSERT INTO appointments (
            user_id,
            appointment_type,
            appointment_date,
            appointment_time,
            advisor_name,
            purpose,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $this->conn->prepare($sql);
        $advisor_name = $data['advisor_name'] ?? null;

        $stmt->bind_param(
            "isssss",
            $user_id,
            $data['service_type'],
            $data['appointment_date'],
            $data['appointment_time'],
            $advisor_name,
            $data['purpose']
        );
        
        if ($stmt->execute()) {
            $appointment_id = $stmt->insert_id;
            $stmt->close();
            return ['success' => true, 'id' => $appointment_id];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'error' => $error];
        }
    }

    public function cancelAppointment($appointment_id) {
        $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $appointment_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'error' => $error];
        }
    }

    public function hasDuplicateAppointment($user_id, $date, $time) {
        $sql = "SELECT id FROM appointments 
                WHERE appointment_date = ? AND appointment_time = ? 
                AND user_id = ? AND LOWER(status) IN ('pending', 'approved')";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $date, $time, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $count = $result->num_rows;
        $stmt->close();
        
        return $count > 0;
    }

    public function getAppointmentStats($user_id) {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'upcoming' => 0
        ];
        
        $sql = "SELECT LOWER(status) AS status, COUNT(*) as count FROM appointments WHERE user_id = ? GROUP BY LOWER(status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $stats[$row['status']] = $row['count'];
            $stats['total'] += $row['count'];
        }
        $stmt->close();
        
        $sql = "SELECT COUNT(*) as count FROM appointments 
                WHERE user_id = ? AND LOWER(status) IN ('pending', 'approved') AND appointment_date >= CURDATE()";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $row = $result->fetch_assoc();
        $stats['upcoming'] = $row['count'];
        $stmt->close();
        
        return $stats;
    }

    public function getAppointmentTypes() {
        $sql = "SELECT * FROM appointment_types WHERE is_active = 1 ORDER BY name";
        $result = $this->conn->query($sql);
        
        $types = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $types[] = $row;
            }
        }
        
        return $types;
    }

    public function updateAppointmentStatus($appointment_id, $status, $purpose = null, ?int $scheduleId = null) {
        $status = strtolower($status);
        if ($scheduleId !== null) {
            $sql = "UPDATE appointments SET status = ?, purpose = COALESCE(?, purpose), schedule_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssii", $status, $purpose, $scheduleId, $appointment_id);
        } elseif ($status === 'approved') {
            $sql = "UPDATE appointments SET status = ?, purpose = COALESCE(?, purpose) WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $purpose, $appointment_id);
        } else {
            $sql = "UPDATE appointments SET status = ?, purpose = COALESCE(?, purpose), schedule_id = NULL WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $purpose, $appointment_id);
        }

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true];
        }

        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'error' => $error];
    }

    public function getAppointmentWithUserEmail(int $appointment_id)
    {
        $sql = "
            SELECT
                a.*, 
                s.email,
                s.name,
                s.student_id,
                a.user_id
            FROM appointments a
            LEFT JOIN students s ON a.user_id = s.id
            WHERE a.id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $appointment ?: null;
    }

    /*
    =========================
        ADMIN FUNCTION (NEW)
    =========================
    */

    public function getAllAppointments() {
        $sql = "
        SELECT 
            a.*,
            a.appointment_type AS service_type,
            s.name AS name,
            s.student_id,
            sc.office,
            sc.id AS linked_schedule_id
        FROM appointments a
        LEFT JOIN students s ON a.user_id = s.id
        LEFT JOIN schedules sc ON a.schedule_id = sc.id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ";
        $result = $this->conn->query($sql);

        $appointments = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }

        return $appointments;
    }

    public function findBestScheduleForAppointment(int $appointmentId): ?int
    {
        $stmt = $this->conn->prepare("
            SELECT appointment_date, appointment_time, appointment_type
            FROM appointments
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $appointment = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$appointment) {
            return null;
        }

        $day = date('l', strtotime($appointment['appointment_date']));
        $time = $appointment['appointment_time'];
        $serviceType = $appointment['appointment_type'];

        $stmt = $this->conn->prepare("
            SELECT sc.id
            FROM schedules sc
            LEFT JOIN appointments a ON a.schedule_id = sc.id AND LOWER(a.status) = 'approved'
            WHERE sc.day_of_week = ?
              AND sc.start_time <= ?
              AND sc.end_time > ?
              AND (sc.service_type IS NULL OR sc.service_type = '' OR sc.service_type = ?)
              AND LOWER(sc.status) = 'available'
            GROUP BY sc.id, sc.max_slots
            HAVING COUNT(a.id) < sc.max_slots
            ORDER BY sc.start_time ASC
            LIMIT 1
        ");
        $stmt->bind_param("ssss", $day, $time, $time, $serviceType);
        $stmt->execute();
        $schedule = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($schedule) {
            return (int) $schedule['id'];
        }

        $endTime = date('H:i:s', strtotime($time . ' +30 minutes'));
        $status = 'Available';
        $maxSlots = 1;
        $office = 'Admin Office';

        $stmt = $this->conn->prepare("
            INSERT INTO schedules (day_of_week, start_time, end_time, office, max_slots, status, service_type)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssiss", $day, $time, $endTime, $office, $maxSlots, $status, $serviceType);
        $stmt->execute();
        $newScheduleId = (int) $this->conn->insert_id;
        $stmt->close();

        return $newScheduleId > 0 ? $newScheduleId : null;
    }
}
