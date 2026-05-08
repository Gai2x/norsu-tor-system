<?php

class UserModel
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function findStudentByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function studentExistsByStudentIdOrEmail(string $studentId, string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM students WHERE student_id = ? OR email = ?");
        $stmt->bind_param("ss", $studentId, $email);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function createGoogleStudent(
        string $name,
        string $email,
        string $studentId,
        string $course,
        string $hashedPassword,
        string $role
    ): int {
        $stmt = $this->conn->prepare(
            "INSERT INTO students(name,email,student_id,course,password,role) VALUES(?,?,?,?,?,?)"
        );
        $stmt->bind_param("ssssss", $name, $email, $studentId, $course, $hashedPassword, $role);
        $stmt->execute();

        return (int) $this->conn->insert_id;
    }
}
