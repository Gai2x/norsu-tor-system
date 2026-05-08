<?php

require_once __DIR__ . '/AdminPageController.php';

class StudentEditPageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Edit Student',
            'Update student account information'
        );

        $conn = $viewData['conn'];

        if (!isset($_GET['id'])) {
            die("Invalid request.");
        }

        $id = intval($_GET['id']);

        if (isset($_POST['update'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $course = $_POST['course'];

            $stmt = $conn->prepare("
                UPDATE students
                SET name = ?, email = ?, course = ?
                WHERE id = ?
            ");

            $stmt->bind_param("sssi", $name, $email, $course, $id);

            if ($stmt->execute()) {
                header("Location: Students.php?updated=1");
                exit();
            }

            $viewData['error'] = "Error updating student.";
        }

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
            'error' => $viewData['error'] ?? null,
        ]);
    }
}
