<?php

require_once __DIR__ . '/../model/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function loginGoogleUser(array $user): void
    {
        $this->startSession();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['course'] = $user['course'];
    }

    public function storePendingGoogleProfile(string $email, string $name): void
    {
        $this->startSession();

        $_SESSION['google_email'] = $email;
        $_SESSION['google_name'] = $name;
    }

    public function clearPendingGoogleProfile(): void
    {
        $this->startSession();

        unset($_SESSION['google_email'], $_SESSION['google_name']);
    }

    public function createGoogleStudent(array $input): array
    {
        $this->startSession();

        if (!isset($input['complete_google'])) {
            return ['errors' => []];
        }

        $name = trim($input['name'] ?? '');
        $studentId = trim($input['student_id'] ?? '');
        $course = trim($input['course'] ?? '');
        $email = $_SESSION['google_email'] ?? '';
        $errors = [];

        if (!preg_match("/^[0-9]{4}-[0-9]{5}$/", $studentId)) {
            $errors[] = "Student ID format must be 2021-12345";
        }

        if (empty($course)) {
            $errors[] = "Select course";
        }

        if ($email === '') {
            $errors[] = "Google session expired.";
        }

        if (empty($errors) && $this->userModel->studentExistsByStudentIdOrEmail($studentId, $email)) {
            $errors[] = "Student already exists.";
        }

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $role = "student";
        $password = password_hash("google123", PASSWORD_DEFAULT);
        $id = $this->userModel->createGoogleStudent($name, $email, $studentId, $course, $password, $role);

        $_SESSION['student_id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['role'] = $role;
        $_SESSION['course'] = $course;

        $this->clearPendingGoogleProfile();

        return [
            'errors' => [],
            'redirect' => '../public/user/Dashboard.php',
        ];
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
