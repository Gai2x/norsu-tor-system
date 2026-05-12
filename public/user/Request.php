<?php
session_start();

require_once __DIR__ . '/../../model/UserRoleModel.php';
UserRoleModel::requireRole(UserRoleModel::ROLE_USER, '../Login.php');

require_once __DIR__ . '/../../database/Connection.php';
require_once __DIR__ . '/../../controller/user/RequestController.php';
require_once __DIR__ . '/../../controller/user/UserLayoutController.php';

$requestController = new RequestController($conn);
$user_id = $_SESSION['user_id'];

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $notes = trim($_POST['notes'] ?? '');
    if ($notes === 'Other...') {
        $notes = trim($_POST['notes_other'] ?? '');
    }

    $result = $requestController->submitRequest(
        $user_id,
        trim($_POST['service_type'] ?? ''),
        $notes,
        trim($_POST['year_level'] ?? ''),
        trim($_POST['contact_number'] ?? ''),
        $_FILES['document'] ?? null
    );

    if ($result['success']) {
        $success_message = $result['success'];
    }
    if ($result['error']) {
        $error_message = $result['error'];
    }
}

if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $result = $requestController->cancelRequest((int) $_GET['cancel'], $user_id);

    if ($result['success']) {
        $success_message = $result['success'];
    }
    if ($result['error']) {
        $error_message = $result['error'];
    }
}

$requests = $requestController->getUserRequests($user_id);
$status_counts = $requestController->getStatusCounts($requests);
$user_data = $requestController->getUserDetails($user_id);
$stats = [
    'upcoming_appointments' => 0,
    'pending_requests' => $status_counts['pending'] ?? 0
];
$userHeader = UserLayoutController::buildHeaderData($conn);

$pageTitle = "My Requests - NORSU Academic Services";
$pageSubtitle = "My Requests";

include __DIR__ . '/../../view/user/request.php';
