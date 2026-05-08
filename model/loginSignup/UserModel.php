<?php

class UserModel {
    private $conn;

    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }

    public function findByEmailOrStudentId($identifier) {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE email = ? OR student_id = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $user;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $user;
    }

    public function existsByEmailOrStudentId($email, $student_id) {
        $stmt = $this->conn->prepare("SELECT id FROM students WHERE email = ? OR student_id = ?");
        $stmt->bind_param("ss", $email, $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function createStudent($name, $email, $student_id, $course, $hashed_password) {
        $stmt = $this->conn->prepare(
            "INSERT INTO students (name, email, student_id, course, password, role)
             VALUES (?, ?, ?, ?, ?, 'student')"
        );
        $stmt->bind_param("sssss", $name, $email, $student_id, $course, $hashed_password);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function updatePasswordByEmail($email, $hashed_password) {
        $stmt = $this->conn->prepare("UPDATE students SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $success = $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $success && $affectedRows >= 0;
    }
}
