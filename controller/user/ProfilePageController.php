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

        if (isset($_POST['update_profile'])) {
            $isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
            $name = trim($_POST['name'] ?? '');
            $middle_name = trim($_POST['middle_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $dob = trim($_POST['dob'] ?? '');
            $course = trim($_POST['course'] ?? '');
            $phone_number = trim($_POST['phone_number'] ?? '');
            $newPassword = (string) ($_POST['new_password'] ?? '');
            $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
            $profile_image = $user['profile_image'] ?? null;
            $errors = [];

            if ($name === '') {
                $errors[] = 'Full name is required.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'A valid email address is required.';
            }

            if ($phone_number !== '' && !preg_match('/^(09\d{9}|\+639\d{9})$/', $phone_number)) {
                $errors[] = 'Invalid phone number format.';
            }

            if ($newPassword !== '' || $confirmPassword !== '') {
                if (strlen($newPassword) < 6) {
                    $errors[] = 'New password must be at least 6 characters long.';
                }
                if ($newPassword !== $confirmPassword) {
                    $errors[] = 'New password and confirmation do not match.';
                }
            }

            if (!empty($_FILES["profile_pic"]["name"])) {
                $imageInfo = @getimagesize($_FILES["profile_pic"]["tmp_name"]);
                $allowedTypes = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif'];

                if ($imageInfo === false || !isset($allowedTypes[$imageInfo[2]])) {
                    $errors[] = 'Please upload a valid PNG, JPG, or GIF image.';
                } elseif ($_FILES["profile_pic"]["size"] > 2 * 1024 * 1024) {
                    $errors[] = 'Image size must be 2MB or less.';
                } else {
                    $fileName = time() . "_student_" . $userId . "." . $allowedTypes[$imageInfo[2]];
                    $targetFile = __DIR__ . "/../../uploads/" . $fileName;

                    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
                        $profile_image = $fileName;
                    } else {
                        $errors[] = 'Unable to upload profile image. Please try again.';
                    }
                }
            }

            if (empty($errors)) {
                if ($newPassword !== '') {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE students SET name=?, email=?, middle_name=?, dob=?, course=?, phone_number=?, profile_image=?, password=? WHERE id=? AND role='student'");
                    $stmt->bind_param("ssssssssi", $name, $email, $middle_name, $dob, $course, $phone_number, $profile_image, $hashedPassword, $userId);
                } else {
                    $stmt = $conn->prepare("UPDATE students SET name=?, email=?, middle_name=?, dob=?, course=?, phone_number=?, profile_image=? WHERE id=? AND role='student'");
                    $stmt->bind_param("sssssssi", $name, $email, $middle_name, $dob, $course, $phone_number, $profile_image, $userId);
                }

                $success = $stmt->execute();
                $stmt->close();

                if ($success) {
                    $_SESSION['name'] = $name;
                    $_SESSION['course'] = $course;
                    $_SESSION['profile_image'] = $profile_image;
                    $_SESSION['phone_number'] = $phone_number;
                    $_SESSION['dob'] = $dob;
                    $_SESSION['middle_name'] = $middle_name;

                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'errors' => []]);
                        exit();
                    }

                    header("Location: profile.php?updated=1");
                    exit();
                }

                $errors[] = 'Unable to update profile at this time.';
            }

            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(422);
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit();
            }

            $_SESSION['error'] = implode(' ', $errors);
            header("Location: profile.php");
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
