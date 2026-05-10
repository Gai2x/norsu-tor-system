<?php

require_once __DIR__ . '/AdminPageController.php';
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../model/admin/AdminModel.php';

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
        $model = new AdminModel($conn);

        return array_merge($viewData, [
            'students' => $students,
            'courses' => $model->getCourses(),
            'yearLevels' => $model->getYearLevels(),
            'statuses' => $model->getStudentStatuses(),
        ]);
    }
}
