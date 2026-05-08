<?php
require_once __DIR__ . '/../../model/user/AppointmentModel.php';
require_once __DIR__ . '/../../database/Connection.php';
require_once __DIR__ . '/../../model/UserRoleModel.php';
class AppointmentController {
    private $model;

    public function __construct($conn) {
        $this->model = new AppointmentModel($conn);
    }

    /*
    =========================
        USER FUNCTIONS
    =========================
    */

    public function getUserAppointments($user_id, $status = null) {
        return $this->model->getUserAppointments($user_id, $status);
    }

    public function getUpcomingAppointments($user_id) {
        return $this->model->getUpcomingAppointments($user_id);
    }

    public function getPastAppointments($user_id, $limit = 10) {
        return $this->model->getPastAppointments($user_id, $limit);
    }

    public function getAppointmentById($appointment_id, $user_id) {
        return $this->model->getAppointmentById($appointment_id, $user_id);
    }

    public function bookAppointment($user_id, $data) {

        if (empty($data['appointment_date']) || empty($data['appointment_time']) || 
            empty($data['service_type']) || empty($data['purpose'])) {
            return ['success' => false, 'message' => 'Please fill in all required fields.'];
        }

        if (strtotime($data['appointment_date']) < strtotime(date('Y-m-d'))) {
            return ['success' => false, 'message' => 'Appointment date cannot be in the past.'];
        }

        if ($this->model->hasDuplicateAppointment(
            $user_id,
            $data['appointment_date'],
            $data['appointment_time']
        )) {
            return ['success' => false, 'message' => 'You already have an appointment scheduled for this date and time.'];
        }

        $result = $this->model->bookAppointment($user_id, $data);

        if ($result['success']) {
            return [
                'success' => true,
                'message' => 'Appointment booked successfully!',
                'id' => $result['id']
            ];
        }

        return [
            'success' => false,
            'message' => 'Error booking appointment: ' . $result['error']
        ];
    }

    public function cancelAppointment($appointment_id, $user_id) {

        $appointment = $this->getAppointmentById($appointment_id, $user_id);

        if (!$appointment) {
            return ['success' => false, 'message' => 'Appointment not found or you don\'t have permission.'];
        }

        if (strtolower($appointment['status']) !== 'pending') {
            return ['success' => false, 'message' => 'Only pending appointments can be cancelled.'];
        }

        $result = $this->model->cancelAppointment($appointment_id);

        if ($result['success']) {
            return ['success' => true, 'message' => 'Appointment cancelled successfully!'];
        }

        return [
            'success' => false,
            'message' => 'Error cancelling appointment: ' . $result['error']
        ];
    }

    public function getAppointmentStats($user_id) {
        return $this->model->getAppointmentStats($user_id);
    }

    public function getAppointmentTypes() {
        return $this->model->getAppointmentTypes();
    }

    /*
    =========================
        ADMIN FUNCTIONS
    =========================
    */

    // ✅ GET ALL APPOINTMENTS (for admin dashboard table)
    public function getAllAppointments() {
        return $this->model->getAllAppointments();
    }

    // ✅ ADMIN: approve appointment
    public function approveAppointment($appointment_id) {
        $scheduleId = $this->model->findBestScheduleForAppointment((int) $appointment_id);
        return $this->updateAppointmentStatus($appointment_id, "approved", null, $scheduleId);
    }

    // ✅ ADMIN: reject/cancel appointment
    public function rejectAppointment($appointment_id) {
        return $this->updateAppointmentStatus($appointment_id, "rejected");
    }

    /*
    =========================
        CORE STATUS UPDATE
    =========================
    */

    public function updateAppointmentStatus($appointment_id, $status, $purpose = null, ?int $scheduleId = null) {

        $status = strtolower($status);
        $allowed_statuses = ['pending', 'approved', 'rejected', 'completed', 'cancelled'];

        if (!in_array($status, $allowed_statuses)) {
            return ['success' => false, 'message' => 'Invalid status.'];
        }

        $result = $this->model->updateAppointmentStatus($appointment_id, $status, $purpose, $scheduleId);

        if ($result['success']) {
            return ['success' => true, 'message' => 'Appointment status updated successfully!'];
        }

        return [
            'success' => false,
            'message' => 'Error updating appointment: ' . $result['error']
        ];
    }

} 

/* ===================================
   HANDLE APPROVE BUTTON
=================================== */
if (isset($_GET['approve'])) {
    UserRoleModel::requireAnyRole([UserRoleModel::ROLE_SUPER_ADMIN, UserRoleModel::ROLE_ADMIN], '../../public/Login.php');

    $controller = new AppointmentController($conn);
    $controller->approveAppointment($_GET['approve']);

    header("Location: ../../public/admin/Appointments.php");
    exit();
}


/* ===================================
   HANDLE REJECT BUTTON
=================================== */
if (isset($_GET['reject'])) {
    UserRoleModel::requireAnyRole([UserRoleModel::ROLE_SUPER_ADMIN, UserRoleModel::ROLE_ADMIN], '../../public/Login.php');

    $controller = new AppointmentController($conn);
    $controller->rejectAppointment($_GET['reject']);

    header("Location: ../../public/admin/Appointments.php");
    exit();
}
