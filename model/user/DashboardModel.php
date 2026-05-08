<?php
// model/DashboardModel.php

class DashboardModel {
    private $conn;
    
    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }
    
    /**
     * Get total requests count for a user
     */
    public function getTotalRequests($user_id) {
        $query = "SELECT COUNT(*) as total FROM requests WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];
        $stmt->close();
        return $total;
    }
    
    /**
     * Get pending requests count for a user
     */
    public function getPendingRequests($user_id) {
        $query = "SELECT COUNT(*) as pending FROM requests WHERE user_id = ? AND status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pending = $result->fetch_assoc()['pending'];
        $stmt->close();
        return $pending;
    }
    
    /**
     * Get approved requests count for a user
     */
    public function getApprovedRequests($user_id) {
        $query = "SELECT COUNT(*) as approved FROM requests WHERE user_id = ? AND status = 'approved'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $approved = $result->fetch_assoc()['approved'];
        $stmt->close();
        return $approved;
    }
    
    /**
     * Get rejected requests count for a user
     */
    public function getRejectedRequests($user_id) {
        $query = "SELECT COUNT(*) as rejected FROM requests WHERE user_id = ? AND status = 'rejected'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rejected = $result->fetch_assoc()['rejected'];
        $stmt->close();
        return $rejected;
    }
    
    /**
     * Get recent requests for a user
     */
    public function getRecentRequests($user_id, $limit = 5) {
        $query = "SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $requests;
    }
    
    /**
     * Get upcoming appointments count for a user
     */
    public function getUpcomingAppointments($user_id) {
        $query = "SELECT COUNT(*) as upcoming FROM appointments 
                  WHERE user_id = ? AND appointment_date >= CURDATE() 
                  AND LOWER(status) IN ('pending', 'approved')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $upcoming = $result->fetch_assoc()['upcoming'];
        $stmt->close();
        return $upcoming;
    }

    public function getUpcomingAppointmentDetails($user_id, $limit = 3) {
        $query = "
            SELECT
                a.*,
                a.appointment_type AS service_type,
                COALESCE(sc.office, a.advisor_name, 'N/A') AS office
            FROM appointments a
            LEFT JOIN schedules sc ON a.schedule_id = sc.id
            WHERE a.user_id = ?
              AND a.appointment_date >= CURDATE()
              AND LOWER(a.status) IN ('pending', 'approved')
            ORDER BY a.appointment_date ASC, a.appointment_time ASC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $appointments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $appointments;
    }
    
    /**
     * Get user information
     */
    public function getUserInfo($user_id) {
        $query = "SELECT name, email, student_id, course FROM students WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_info = $result->fetch_assoc();
        $stmt->close();
        return $user_info;
    }
    
    /**
     * Get all dashboard statistics in one call
     */
    public function getDashboardStats($user_id) {
        return [
            'total_requests' => $this->getTotalRequests($user_id),
            'pending_requests' => $this->getPendingRequests($user_id),
            'approved_requests' => $this->getApprovedRequests($user_id),
            'rejected_requests' => $this->getRejectedRequests($user_id),
            'upcoming_appointments' => $this->getUpcomingAppointments($user_id)
        ];
    }
    
    /**
     * Get request status distribution for chart
     */
    public function getRequestStatusDistribution($user_id) {
        $query = "SELECT status, COUNT(*) as count 
                  FROM requests 
                  WHERE user_id = ? 
                  GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $distribution = [];
        while ($row = $result->fetch_assoc()) {
            $distribution[$row['status']] = $row['count'];
        }
        $stmt->close();
        return $distribution;
    }
    
    /**
     * Get monthly request trends
     */
    public function getMonthlyRequestTrends($user_id, $months = 6) {
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
                         COUNT(*) as count,
                         SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                         SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                  FROM requests 
                  WHERE user_id = ? 
                    AND created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $months);
        $stmt->execute();
        $result = $stmt->get_result();
        $trends = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $trends;
    }
}
?>
