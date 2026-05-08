<?php

require_once __DIR__ . '/AdminPageController.php';
require_once __DIR__ . '/AdminController.php';

class StudentsPageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Students',
            'Registered Student Accounts'
        );

        $conn = $viewData['conn'];
        AdminController::updateRequestStatus($conn);

        $students = mysqli_query($conn, "
            SELECT *
            FROM students
            WHERE role = 'student'
            ORDER BY id DESC
        ");

        return array_merge($viewData, [
            'students' => $students,
        ]);
    }
}
