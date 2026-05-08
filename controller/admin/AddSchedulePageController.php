<?php

require_once __DIR__ . '/AdminPageController.php';
require_once __DIR__ . '/../../model/admin/ScheduleModel.php';

class AddSchedulePageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Add Schedule',
            'Create New Schedule Slot'
        );

        $conn = $viewData['conn'];
        $error = null;

        if (isset($_POST['save_schedule'])) {
            $scheduleModel = new ScheduleModel($conn);
            $result = $scheduleModel->create($_POST);

            if ($result['success']) {
                header("Location: Schedule.php?success=1");
                exit();
            }

            $error = implode(' ', $result['errors']);
        }

        return array_merge($viewData, [
            'error' => $error,
        ]);
    }
}
