<?php


require_once __DIR__ . '/../../model/user/DashboardModel.php';

class DashboardController {
    private $model;
    private $conn;
    
    public function __construct($database_connection) {
        $this->conn = $database_connection;
        $this->model = new DashboardModel($database_connection);
    }
    
    /**
     * Get all dashboard data for the view
     * @param int $user_id User ID
     * @return array Dashboard data
     */
    public function getDashboardData($user_id) {
        return [
            'stats' => $this->model->getDashboardStats($user_id),
            'recent_requests' => $this->model->getRecentRequests($user_id, 5),
            'user_info' => $this->model->getUserInfo($user_id),
            'status_distribution' => $this->model->getRequestStatusDistribution($user_id),
            'monthly_trends' => $this->model->getMonthlyRequestTrends($user_id),
            'upcoming_appointment_details' => $this->model->getUpcomingAppointmentDetails($user_id, 3)
        ];
    }
    
    /**
     * Get welcome message based on time of day
     * @return string Welcome message
     */
    public function getWelcomeMessage() {
        $hour = date('H');
        if ($hour < 12) {
            return "Good morning";
        } elseif ($hour < 18) {
            return "Good afternoon";
        } else {
            return "Good evening";
        }
    }
    
    /**
     * Calculate completion rate
     * @param array $stats Dashboard statistics
     * @return array Completion rate data
     */
    public function getCompletionRate($stats) {
        $completed = $stats['approved_requests'] + $stats['rejected_requests'];
        $total = $stats['total_requests'];
        $rate = $total > 0 ? round(($completed / $total) * 100) : 0;
        
        return [
            'rate' => $rate,
            'completed' => $completed,
            'total' => $total,
            'message' => $this->getCompletionRateMessage($rate)
        ];
    }
    
    /**
     * Get friendly message based on completion rate
     */
    private function getCompletionRateMessage($rate) {
        if ($rate == 0) {
            return "Start by creating your first request";
        } elseif ($rate < 50) {
            return "Keep going! You're making progress";
        } elseif ($rate < 80) {
            return "Great job! Almost there";
        } else {
            return "Excellent! High completion rate";
        }
    }
    
    /**
     * Validate user session
     * @return bool Is user logged in
     */
    public function isUserLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get user ID from session
     * @return int|null User ID
     */
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get user name
     * @return string User name
     */
    public function getUserName() {
        return $_SESSION['name'] ?? 'User';
    }
    
    /**
     * Get first name
     * @return string First name
     */
    public function getFirstName() {
        $name = $this->getUserName();
        return explode(' ', $name)[0];
    }
}
?>
