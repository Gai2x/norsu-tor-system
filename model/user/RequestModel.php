<?php

class RequestModel {
    private $conn;

    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }

    public function createRequest($user_id, $service_type, $notes, $year_level, $contact_number, $uploaded_file) {
        $query = "INSERT INTO requests (user_id, service_type, notes, year_level, contact_number, document_file, status, created_at)
                  VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssss", $user_id, $service_type, $notes, $year_level, $contact_number, $uploaded_file);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function cancelPendingRequest($request_id, $user_id) {
        $query = "UPDATE requests SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $request_id, $user_id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows > 0;
    }

    public function getUserRequests($user_id) {
        $query = "SELECT r.*, s.course FROM requests r LEFT JOIN students s ON r.user_id = s.id WHERE r.user_id = ? ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $requests;
    }

    public function getUserDetails($user_id) {
        $query = "SELECT name, course, email, phone_number FROM students WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_data = $stmt->get_result()->fetch_assoc() ?: [];
        $stmt->close();

        return $user_data;
    }
    
        public function getAllRequests() {

    $query = "SELECT r.*, s.name, s.student_id, s.course FROM requests r LEFT JOIN students s ON r.user_id = s.id ORDER BY r.created_at DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();
    $requests = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $requests;
}

    public function getAllRequestsWithGuests(){
        
        $sql = "

        SELECT
            r.id,
            s.student_id,
            s.name,
            r.service_type,
            r.notes,
            r.status,
            r.created_at,
            'student' AS request_source
        FROM requests r
        LEFT JOIN students s ON r.user_id = s.id

        UNION ALL

        SELECT
            o.id,
            o.student_id,
            o.fullname AS name,
            o.service_type,
            o.notes,
            o.status,
            o.created_at,
            'one_time' AS request_source
        FROM one_time_requests o

        ORDER BY created_at DESC

        ";

        return $this->conn->query($sql);
    }

}
