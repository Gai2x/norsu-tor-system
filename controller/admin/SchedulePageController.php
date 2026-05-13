<?php

require_once __DIR__ . '/AdminPageController.php';
require_once __DIR__ . '/../../model/admin/ScheduleModel.php';

class SchedulePageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Schedule',
            'Manage Appointment Schedules',
            [],
            'schedule'
        );

        $conn = $viewData['conn'];
        $scheduleModel = new ScheduleModel($conn);
        $notice = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create_schedule') {
                $result = $scheduleModel->create($_POST);
                $notice = $result['success'] ? 'Schedule created.' : null;
                $errors = $result['errors'];
            } elseif ($action === 'update_schedule') {
                $result = $scheduleModel->update((int) ($_POST['schedule_id'] ?? 0), $_POST);
                $notice = $result['success'] ? 'Schedule updated.' : null;
                $errors = $result['errors'];
            } elseif ($action === 'delete_schedule') {
                $result = $scheduleModel->delete((int) ($_POST['schedule_id'] ?? 0));
                $notice = $result['success'] ? 'Schedule deleted.' : null;
                $errors = $result['errors'];
            }
        }

        $approvedAppointments = $scheduleModel->getApprovedAppointments();
        $appointmentsBySchedule = $scheduleModel->getAppointmentsBySchedule();
        $weeklySchedules = $scheduleModel->getWeeklySchedules();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $totalSlots = 0;
        $totalDays = 0;

        foreach ($days as $day) {
            if (!empty($weeklySchedules[$day])) {
                $totalDays++;
                $totalSlots += count($weeklySchedules[$day]);
            }
        }

        return array_merge($viewData, [
            'weeklySchedules' => $weeklySchedules,
            'approvedAppointments' => $approvedAppointments,
            'appointmentsBySchedule' => $appointmentsBySchedule,
            'days' => $days,
            'totalSlots' => $totalSlots,
            'totalDays' => $totalDays,
            'notice' => $notice,
            'errors' => $errors,
        ]);
    }
}
