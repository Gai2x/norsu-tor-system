<?php

require_once __DIR__ . '/../../model/loginSignup/OtpModel.php';

class OtpController
{
    private $otpModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->otpModel = new OtpModel();
    }

    public function handle($requestMethod, array $input)
    {
        if ($this->otpModel->getResetData() === null) {
            $_SESSION['password_reset_error'] = 'Start the password reset process first.';
            header('Location: ForgotPassword.php');
            exit();
        }

        if ($this->otpModel->isExpired()) {
            $this->otpModel->clear();
            $_SESSION['password_reset_error'] = 'OTP expired. Please request a new code.';
            header('Location: ForgotPassword.php');
            exit();
        }

        $viewData = [
            'message' => $this->consumeFlash('otp_error'),
            'success' => $this->consumeFlash('password_reset_success'),
            'remainingSeconds' => $this->otpModel->getRemainingSeconds(),
        ];

        if ($requestMethod !== 'POST') {
            return $viewData;
        }

        $otp = trim($input['otp'] ?? '');
        if ($otp === '') {
            $viewData['message'] = 'OTP is required.';
            return $viewData;
        }

        if (!preg_match('/^\d{6}$/', $otp)) {
            $viewData['message'] = 'OTP must be a 6-digit code.';
            return $viewData;
        }

        if (!$this->otpModel->verifyOtp($otp)) {
            $viewData['message'] = 'Wrong or expired OTP.';
            $viewData['remainingSeconds'] = $this->otpModel->getRemainingSeconds();
            return $viewData;
        }

        header('Location: ResetPassword.php');
        exit();
    }

    private function consumeFlash($key)
    {
        $value = $_SESSION[$key] ?? '';
        unset($_SESSION[$key]);
        return $value;
    }
}
