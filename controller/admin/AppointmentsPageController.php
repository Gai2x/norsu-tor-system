<?php

require_once __DIR__ . '/AdminPageController.php';
require_once __DIR__ . '/../user/AppointmentController.php';
require_once __DIR__ . '/../../model/admin/AdminModel.php';

class AppointmentsPageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Appointments',
            'Manage Student Appointments'
        );

        $conn = $viewData['conn'];
        $appointmentController = new AppointmentController($conn);
        $adminModel = new AdminModel($conn);
        $appointments = $appointmentController->getAllAppointments();

        $totalAppointments = 0;
        $pendingAppointments = 0;
        $approvedAppointments = 0;
        $rejectedAppointments = 0;

        foreach ($appointments as $app) {
            $status = strtolower($app['status'] ?? 'pending');
            $totalAppointments++;
            if ($status === "pending") {
                $pendingAppointments++;
            } elseif ($status === "approved") {
                $approvedAppointments++;
            } else {
                $rejectedAppointments++;
            }
        }

        $viewData['stats']['pending_appointments'] = (int) $pendingAppointments;

        return array_merge($viewData, [
            'appointments' => $appointments,
            'totalAppointments' => $totalAppointments,
            'pendingAppointments' => $pendingAppointments,
            'approvedAppointments' => $approvedAppointments,
            'rejectedAppointments' => $rejectedAppointments,
            'appointmentTypes' => $adminModel->getAppointmentTypes(),
            'courses' => $adminModel->getCourses(),
            'yearLevels' => $adminModel->getYearLevels(),
        ]);
    }
}
