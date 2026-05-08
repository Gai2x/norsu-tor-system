<?php

require_once __DIR__ . '/../service/GoogleAuthService.php';
require_once __DIR__ . '/AuthController.php';

class GoogleAuthController
{
    private GoogleAuthService $googleAuthService;
    private AuthController $authController;
    private UserModel $userModel;

    public function __construct(
        GoogleAuthService $googleAuthService,
        AuthController $authController,
        UserModel $userModel
    ) {
        $this->googleAuthService = $googleAuthService;
        $this->authController = $authController;
        $this->userModel = $userModel;
    }

    public function redirectToGoogle(): void
    {
        header('Location: ' . $this->googleAuthService->getAuthUrl());
        exit();
    }

    public function handleCallback(array $query): void
    {
        if (!isset($query['code'])) {
            die("No Google authorization code received.");
        }

        $token = $this->googleAuthService->fetchAccessToken($query['code']);

        if (isset($token['error'])) {
            echo "<pre>";
            print_r($token);
            exit();
        }

        $profile = $this->googleAuthService->getUserProfile($token['access_token']);
        $user = $this->userModel->findStudentByEmail($profile['email']);

        if ($user) {
            $this->authController->loginGoogleUser($user);
            if ($user['role'] === 'super_admin') {
                header("Location: ../public/superadmin/Dashboard.php");
            } elseif ($user['role'] === 'admin') {
                header("Location: ../public/admin/Dashboard.php");
            } else {
                header("Location: ../public/user/Dashboard.php");
            }
            exit();
        }

        $this->authController->storePendingGoogleProfile($profile['email'], $profile['name']);
        header("Location: CompleteGoogleProfile.php");
        exit();
    }
}
