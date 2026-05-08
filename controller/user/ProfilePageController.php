<?php

require_once __DIR__ . '/UserLayoutController.php';
require_once __DIR__ . '/../../model/UserRoleModel.php';

class ProfilePageController
{
    public static function load()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require __DIR__ . '/../../database/Connection.php';

        UserRoleModel::requireRole(UserRoleModel::ROLE_USER, '../Login.php');

        if (isset($_POST['update_profile'])) {
            $name = $_POST['name'];
            $middle_name = $_POST['middle_name'];
            $email = $_POST['email'];
            $dob = $_POST['dob'];
            $course = $_POST['course'];
            $phone_number = $_POST['phone_number'];
            $profile_image = $user['profile_image'] ?? null;

            $phone_number = $_POST['phone_number'];

            if (!preg_match('/^(09\d{9}|\+639\d{9})$/', $phone_number)) {
                $_SESSION['error'] = "Invalid phone number format.";
                header("Location: profile.php");
                exit();
            }

            if (!empty($_FILES["profile_pic"]["name"])) {
                $targetDir = "../../uploads/";
                $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
                    $profile_image = $fileName;
                }
            }

            $stmt = $conn->prepare("UPDATE students SET name=?, email=?,middle_name=?, dob=?, course=?, phone_number=?, profile_image=? WHERE id=?");
            $stmt->bind_param("sssssssi", $name, $email, $middle_name, $dob, $course, $phone_number, $profile_image, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();

            $_SESSION['name'] = $name;
            $_SESSION['course'] = $course;
            $_SESSION['profile_image'] = $profile_image;
            $_SESSION['phone_number'] = $phone_number;
            $_SESSION['dob'] = $dob;
            $_SESSION['middle_name'] = $middle_name;

            header("Location: profile.php?updated=1");
            exit();
        }

        $pageTitle = "My Profile - NORSU Academic Services";
        $pageSubtitle = "Student Appointment & Academic Services";
        $userId = (int) $_SESSION['user_id'];
        $user = null;
        $stats = [
            'upcoming_appointments' => 0,
            'pending_requests' => 0
        ];

        $userStatement = $conn->prepare(
            "SELECT id, student_id, name, middle_name, dob, course, email, phone_number, profile_image
             FROM students
             WHERE id = ?
             LIMIT 1"
        );
        $userStatement->bind_param("i", $userId);
        $userStatement->execute();
        $userResult = $userStatement->get_result();
        $user = $userResult->fetch_assoc();
        $userStatement->close();

        if (!$user) {
            session_unset();
            session_destroy();
            header("Location: ../Login.php");
            exit();
        }

        $pendingRequestStatement = $conn->prepare(
            "SELECT COUNT(*) AS total
             FROM requests
             WHERE user_id = ? AND status = 'pending'"
        );
        $pendingRequestStatement->bind_param("i", $userId);
        $pendingRequestStatement->execute();
        $pendingRequestResult = $pendingRequestStatement->get_result();
        $stats['pending_requests'] = (int) (($pendingRequestResult->fetch_assoc()['total'] ?? 0));
        $pendingRequestStatement->close();

        $upcomingAppointmentStatement = $conn->prepare(
            "SELECT COUNT(*) AS total
             FROM appointments
             WHERE user_id = ?
               AND appointment_date >= CURDATE()
               AND LOWER(status) IN ('pending', 'approved')"
        );
        $upcomingAppointmentStatement->bind_param("i", $userId);
        $upcomingAppointmentStatement->execute();
        $upcomingAppointmentResult = $upcomingAppointmentStatement->get_result();
        $stats['upcoming_appointments'] = (int) (($upcomingAppointmentResult->fetch_assoc()['total'] ?? 0));
        $upcomingAppointmentStatement->close();

        $fullName = trim((string) ($user['name'] ?? ''));
        $nameParts = preg_split('/\s+/', $fullName, -1, PREG_SPLIT_NO_EMPTY);
        $firstName = $nameParts[0] ?? '';
        $lastName = count($nameParts) > 1 ? ($nameParts[count($nameParts) - 1] ?? '') : '';

        $displayFirstName = $firstName !== '' ? $firstName : 'Not provided';
        $displayMiddleName = !empty($user['middle_name']) ? $user['middle_name'] : 'Not provided';
        $displayLastName = $lastName !== '' ? $lastName : 'Not provided';
        $displayEmail = trim((string) ($user['email'] ?? '')) !== '' ? $user['email'] : 'Not provided';
        $displayCourse = trim((string) ($user['course'] ?? '')) !== '' ? $user['course'] : 'Student';
        $displayStudentId = trim((string) ($user['student_id'] ?? '')) !== '' ? $user['student_id'] : 'Not assigned';
        $displayDateOfBirth = !empty($user['dob']) ? $user['dob'] : 'Not provided';
        $displayPhoneNumber = !empty($user['phone_number']) ? $user['phone_number'] : 'Not provided';
        $profileInitial = strtoupper(substr($displayFirstName !== 'Not provided' ? $displayFirstName : 'S', 0, 1));

        $userHeader = UserLayoutController::buildHeaderData($conn);

        return [
            'pageTitle' => $pageTitle,
            'pageSubtitle' => $pageSubtitle,
            'stats' => $stats,
            'user' => $user,
            'fullName' => $fullName,
            'displayFirstName' => $displayFirstName,
            'displayMiddleName' => $displayMiddleName,
            'displayLastName' => $displayLastName,
            'displayEmail' => $displayEmail,
            'displayCourse' => $displayCourse,
            'displayStudentId' => $displayStudentId,
            'displayDateOfBirth' => $displayDateOfBirth,
            'displayPhoneNumber' => $displayPhoneNumber,
            'profileInitial' => $profileInitial,
            'userHeader' => $userHeader,
        ];
    }
}
