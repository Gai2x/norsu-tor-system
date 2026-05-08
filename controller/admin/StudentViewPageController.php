<?php

require_once __DIR__ . '/AdminPageController.php';

class StudentViewPageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Student Details',
            'Student account information'
        );

        $conn = $viewData['conn'];

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            die("Invalid student ID.");
        }

        $id = intval($_GET['id']);

        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $student = $result->fetch_assoc();

        if (!$student) {
            die("Student not found.");
        }

        return array_merge($viewData, [
            'student' => $student,
        ]);
    }
}
