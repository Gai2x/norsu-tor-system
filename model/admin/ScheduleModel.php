<?php

class ScheduleModel
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getWeeklySchedules(): array
    {
        $result = $this->conn->query("
            SELECT
                sc.*,
                COUNT(a.id) AS booked_slots
            FROM schedules sc
            LEFT JOIN appointments a ON a.schedule_id = sc.id AND LOWER(a.status) = 'approved'
            GROUP BY sc.id
            ORDER BY FIELD(sc.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), sc.start_time ASC
        ");

        $weeklySchedules = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $weeklySchedules[$row['day_of_week']][] = $row;
            }
        }

        return $weeklySchedules;
    }

    public function getApprovedAppointments(): array
    {
        $result = $this->conn->query("
            SELECT
                a.*,
                a.appointment_type AS service_type,
                s.name AS student_name,
                s.student_id,
                sc.office,
                sc.day_of_week,
                sc.start_time,
                sc.end_time
            FROM appointments a
            LEFT JOIN students s ON a.user_id = s.id
            LEFT JOIN schedules sc ON a.schedule_id = sc.id
            WHERE LOWER(a.status) = 'approved'
            ORDER BY a.appointment_date ASC, a.appointment_time ASC
        ");

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAppointmentsBySchedule(): array
    {
        $appointments = [];
        foreach ($this->getApprovedAppointments() as $appointment) {
            $scheduleId = (int) ($appointment['schedule_id'] ?? 0);
            if ($scheduleId > 0) {
                $appointments[$scheduleId][] = $appointment;
            }
        }

        return $appointments;
    }

    public function create(array $input): array
    {
        $data = $this->normalizeInput($input);
        $errors = $this->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $stmt = $this->conn->prepare("
            INSERT INTO schedules (day_of_week, start_time, end_time, office, max_slots, status, service_type)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssiss",
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time'],
            $data['office'],
            $data['max_slots'],
            $data['status'],
            $data['service_type']
        );
        $success = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();

        return ['success' => $success, 'errors' => $success ? [] : [$error ?: 'Unable to create schedule.']];
    }

    public function update(int $id, array $input): array
    {
        $data = $this->normalizeInput($input);
        $errors = $this->validate($data);
        if ($id <= 0) {
            $errors[] = 'Invalid schedule.';
        }
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $stmt = $this->conn->prepare("
            UPDATE schedules
            SET day_of_week = ?, start_time = ?, end_time = ?, office = ?, max_slots = ?, status = ?, service_type = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "ssssissi",
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time'],
            $data['office'],
            $data['max_slots'],
            $data['status'],
            $data['service_type'],
            $id
        );
        $success = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();

        return ['success' => $success, 'errors' => $success ? [] : [$error ?: 'Unable to update schedule.']];
    }

    public function delete(int $id): array
    {
        $stmt = $this->conn->prepare("UPDATE appointments SET schedule_id = NULL WHERE schedule_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare("DELETE FROM schedules WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();

        return ['success' => $success, 'errors' => $success ? [] : [$error ?: 'Unable to delete schedule.']];
    }

    private function normalizeInput(array $input): array
    {
        return [
            'day_of_week' => trim($input['day_of_week'] ?? ''),
            'start_time' => trim($input['start_time'] ?? ''),
            'end_time' => trim($input['end_time'] ?? ''),
            'office' => trim($input['office'] ?? ''),
            'max_slots' => max(1, (int) ($input['max_slots'] ?? 1)),
            'status' => trim($input['status'] ?? 'Available'),
            'service_type' => trim($input['service_type'] ?? ''),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['day_of_week'] === '') {
            $errors[] = 'Day of week is required.';
        }
        if ($data['start_time'] === '' || $data['end_time'] === '') {
            $errors[] = 'Start and end time are required.';
        }
        if ($data['start_time'] !== '' && $data['end_time'] !== '' && $data['start_time'] >= $data['end_time']) {
            $errors[] = 'End time must be after start time.';
        }
        if ($data['office'] === '') {
            $errors[] = 'Office is required.';
        }
        if (!in_array($data['status'], ['Available', 'Full'], true)) {
            $errors[] = 'Invalid schedule status.';
        }

        return $errors;
    }
}
