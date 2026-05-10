<?php
session_start();

require_once __DIR__ . '/../../model/UserRoleModel.php';
UserRoleModel::requireRole(UserRoleModel::ROLE_USER, '../Login.php');

$pageTitle = "My Appointments - NORSU Academic Services";
$pageSubtitle = "My Appointments";

include '../../database/Connection.php';
require_once '../../controller/user/AppointmentController.php';
require_once '../../controller/user/UserLayoutController.php';

$appointmentController = new AppointmentController($conn);
$user_id = $_SESSION['user_id'];

$message = '';
$messageType = '';

if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $result = $appointmentController->cancelAppointment((int) $_GET['cancel'], $user_id);
    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'error';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $data = [
        'appointment_date' => trim($_POST['appointment_date'] ?? ''),
        'appointment_time' => trim($_POST['appointment_time'] ?? ''),
        'service_type' => trim($_POST['service_type'] ?? ''),
    ];

    $result = $appointmentController->bookAppointment($data, $user_id);

    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'error';

    if ($result['success']) {
        $_POST = [];
    }
}

$upcoming_appointments = $appointmentController->getUpcomingAppointments($user_id);
$past_appointments = $appointmentController->getPastAppointments($user_id, 10);
$appointment_types = $appointmentController->getAppointmentTypes();
$appointment_stats = $appointmentController->getAppointmentStats($user_id);
$stats = [
    'upcoming_appointments' => count($upcoming_appointments),
    'pending_requests' => 0
];
$userHeader = UserLayoutController::buildHeaderData($conn);

include __DIR__ . '/../../view/user/appointments.php';
