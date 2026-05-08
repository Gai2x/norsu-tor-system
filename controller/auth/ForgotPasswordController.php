<?php

require_once __DIR__ . '/../../database/connection.php';
require_once __DIR__ . '/../../model/loginSignup/UserModel.php';
require_once __DIR__ . '/../../model/loginSignup/OtpModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ForgotPasswordController
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
            // echo "STEP 1: Controller reached<br>";

            $viewData = [
                'message' => $this->consumeFlash('password_reset_error'),
                'success' => $this->consumeFlash('password_reset_success'),
                'email' => '',
            ];

            if ($requestMethod !== 'POST') {
                // echo "STEP 2: Not POST or send not set<br>";
                return $viewData;
            }

            // echo "STEP 3: POST detected<br>";

            $email = trim($input['email'] ?? '');
            $viewData['email'] = $email;

            if ($email === '') {
                // echo "STEP 4: Email empty<br>";
                $viewData['message'] = 'Email is required.';
                return $viewData;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // echo "STEP 5: Invalid email format<br>";
                $viewData['message'] = 'Please enter a valid email address.';
                return $viewData;
            }

            // echo "STEP 6: Email validated<br>";

            $user = $this->userModel->findByEmail($email);
            if (!$user) {
                // echo "STEP 7: User not found<br>";
                $viewData['message'] = 'Email not found.';
                return $viewData;
            }

            // echo "STEP 8: User found<br>";

            $otp = $this->otpModel->generateOtp();
            $this->otpModel->storeOtp($email, $otp);

            // echo "STEP 9: OTP generated and stored<br>";

            try {
                // echo "STEP 10: Before sending email<br>";

                $this->sendOtpEmail($email, $otp);

                // echo "STEP 11: Email sent successfully<br>";

                $_SESSION['reset_email'] = $email;
                $_SESSION['password_reset_success'] = 'OTP sent successfully. Check your email for the verification code.';

                session_write_close();

                header('Location: VerifyOtp.php');
                exit();

            } catch (\Throwable $exception) {

                // echo "STEP 12: ERROR OCCURRED<br>";

                $this->otpModel->clear();

                $viewData['message'] = 'SMTP ERROR: ' . $exception->getMessage();

                return $viewData;
            }
        }

    private function sendOtpEmail($email, $otp)
    {
        $mail = new PHPMailer(true);

        // 🔥 FORCE DEBUG (temporary but important)
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function ($str, $level) {
            error_log("SMTP[$level] $str");
        };

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'dzdanielsaavedra@gmail.com';

        // ⚠️ IMPORTANT: REMOVE SPACES (critical fix)
        $mail->Password = 'oebgksjlsdqnoerd';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('dzdanielsaavedra@gmail.com', 'NORSU System');
        $mail->addAddress($email);

        $mail->isHTML(false);
        $mail->Subject = 'Password Reset OTP';

        $mail->Body =
            "Hello Student,\n\n" .
            "Your OTP for password reset is: {$otp}\n\n" .
            "This code expires in 10 minutes.\nDo not share this code.\n\n" .
            "-NORSU Appointment System";

        // 🔥 THIS MAKES FAILURES EXPLICIT
        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo ?: 'SMTP send failed (no details)');
        }
    }
    private function consumeFlash($key)
    {
        if (!isset($_SESSION[$key])) {
            return '';
        }

        $value = $_SESSION[$key];
        unset($_SESSION[$key]);

        return $value;
    }

}
