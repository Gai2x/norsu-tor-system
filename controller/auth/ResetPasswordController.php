<?php

require_once __DIR__ . '/../../database/connection.php';
require_once __DIR__ . '/../../model/loginSignup/UserModel.php';
require_once __DIR__ . '/../../model/loginSignup/OtpModel.php';

class ResetPasswordController
{
    private $userModel;
    private $otpModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userModel = new UserModel($GLOBALS['conn']);
        $this->otpModel = new OtpModel();
    }

    public function handle($requestMethod, array $input)
    {
        if (!$this->otpModel->isVerified()) {
            $_SESSION['password_reset_error'] = 'Verify your OTP before resetting your password.';
            header('Location: ForgotPassword.php');
            exit();
        }

        $viewData = [
            'message' => $this->consumeFlash('reset_error'),
        ];

        if ($requestMethod !== 'POST') {
            return $viewData;
        }

        $password = (string) ($input['password'] ?? '');
        $confirm = (string) ($input['confirm'] ?? '');

        if ($password === '' || $confirm === '') {
            $viewData['message'] = 'Both password fields are required.';
            return $viewData;
        }

        if ($password !== $confirm) {
            $viewData['message'] = 'Passwords do not match.';
            return $viewData;
        }

        if (strlen($password) < 6) {
            $viewData['message'] = 'Password must be at least 6 characters.';
            return $viewData;
        }

        $email = $this->otpModel->getEmail();
        if (!$email) {
            $this->otpModel->clear();
            $_SESSION['password_reset_error'] = 'Password reset session was lost. Please start again.';
            header('Location: ForgotPassword.php');
            exit();
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        if (!$this->userModel->updatePasswordByEmail($email, $hash)) {
            $viewData['message'] = 'Failed to reset password.';
            return $viewData;
        }

        $this->otpModel->clear();
        $_SESSION['login_success'] = 'Password updated successfully. You can now log in.';
        header('Location: Login.php');
        exit();
    }

    private function consumeFlash($key)
    {
        $value = $_SESSION[$key] ?? '';
        unset($_SESSION[$key]);
        return $value;
    }
}
