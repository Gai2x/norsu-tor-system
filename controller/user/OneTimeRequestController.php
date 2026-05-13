<?php
require_once __DIR__ . '/../../database/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $student_id   = trim($_POST['student_id'] ?? '');
    $fullname     = trim($_POST['fullname'] ?? '');
    $contact      = trim($_POST['contact'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $service_type = trim($_POST['service_type'] ?? '');
    $notes        = trim($_POST['notes'] ?? '');
    $notes_other  = trim($_POST['notes_other'] ?? '');
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $appointment_time = trim($_POST['appointment_time'] ?? '');

    $errors = [];
    $allowedCategories = ['Document Request', 'Appointment'];

    /* =========================
       REQUIRED FIELDS
    ========================= */
    if (
        empty($student_id) ||
        empty($fullname) ||
        empty($contact) ||
        empty($email) ||
        empty($category) ||
        empty($service_type)
    ) {
        $errors[] = "Please fill in all required fields.";
    }

    if (!in_array($category, $allowedCategories, true)) {
        $errors[] = "Please select a valid category.";
    }

    if ($category === 'Appointment') {
        if ($appointment_date === '') {
            $errors[] = "Appointment date is required.";
        }

        if ($appointment_time === '') {
            $errors[] = "Appointment time is required.";
        }
    } else {
        $appointment_date = '';
        $appointment_time = '';
    }

    if ($notes === 'Other...') {
        $notes = $notes_other;
        if ($notes === '') {
            $errors[] = "Please enter the purpose of your request.";
        }
    }

    /* =========================
       STUDENT ID VALIDATION
       Example: 2021-12345
    ========================= */
    if (empty($student_id)) {
        $errors[] = "Student ID is required.";
    } elseif (!preg_match("/^[0-9]{4}-[0-9]{5}$/", $student_id)) {
        $errors[] = "Student ID must follow format: 2021-12345";
    }

    /* =========================
       FULL NAME VALIDATION
       letters + spaces only
    ========================= */
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $fullname)) {
        $errors[] = "Full name can only contain letters and spaces.";
    } elseif (strlen($fullname) < 5) {
        $errors[] = "Full name is too short.";
    }

    /* =========================
       PHONE VALIDATION
       Example: 09123456789
    ========================= */
    if (empty($contact)) {
        $errors[] = "Contact number is required.";
    } elseif (!preg_match("/^09[0-9]{9}$/", $contact)) {
        $errors[] = "Contact number must be a valid PH number (09123456789).";
    }

    /* =========================
       EMAIL VALIDATION
    ========================= */
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    $storedNotes = trim($notes);
    $noteParts = ["Category: {$category}"];
    if ($category === 'Appointment') {
        $noteParts[] = "Appointment Date: {$appointment_date}";
        $noteParts[] = "Appointment Time: {$appointment_time}";
    }
    if ($storedNotes !== '') {
        $noteParts[] = "Purpose: {$storedNotes}";
    }
    $storedNotes = implode("\n", $noteParts);

    /* =========================
       SHOW ERRORS
    ========================= */
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;

        header("Location: /Norsu_Tor/view/public/oneTimeRequest.php");
        exit();
    }

    /* =========================
       INSERT TO DATABASE
    ========================= */
    $sql = "INSERT INTO one_time_requests
            (
                student_id,
                fullname,
                contact,
                email,
                service_type,
                notes,
                status,
                created_at
            )
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssss",
        $student_id,
        $fullname,
        $contact,
        $email,
        $service_type,
        $storedNotes
    );

    if ($stmt->execute()) {

        header("Location: /Norsu_Tor/public/OneTimeRequestSuccess.php");
        exit();

    } else {

        die("Insert failed: " . $stmt->error);
    }
}
?>
