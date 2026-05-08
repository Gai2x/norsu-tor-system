<?php
session_start();

require_once __DIR__ . '/../../model/UserRoleModel.php';
UserRoleModel::requireRole(UserRoleModel::ROLE_USER, '../Login.php');

require_once '../../database/Connection.php';
require_once '../../controller/user/DashboardController.php';
require_once '../../controller/user/UserLayoutController.php';

$dashboardController = new DashboardController($conn);
$user_id = $_SESSION['user_id'];

$dashboardData = $dashboardController->getDashboardData($user_id);
$stats = $dashboardData['stats'];
$recent_requests = $dashboardData['recent_requests'];
$user_info = $dashboardData['user_info'];
$completionData = $dashboardController->getCompletionRate($stats);
$welcomeMessage = $dashboardController->getWelcomeMessage();
$firstName = $dashboardController->getFirstName();
$userHeader = UserLayoutController::buildHeaderData($conn);

$pageTitle = "Student Dashboard - NORSU Academic Services";
$pageSubtitle = "Student Appointment & Academic Services";

include __DIR__ . '/../../view/user/dashboard.php';
