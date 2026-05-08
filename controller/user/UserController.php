<?php

require_once __DIR__ . '/../../database/Connection.php';
require_once __DIR__ . '/../../model/loginSignup/UserModel.php';

class UserController {
    private $model;

    public function __construct() {
        global $conn;
        $this->model = new UserModel($conn);
    }

    public function login(array $input) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;

        if (isset($input['login'])) {
            $email = trim($input['email'] ?? '');
            $password = trim($input['password'] ?? '');
            $user = $this->model->findByEmailOrStudentId($email);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['course'] = $user['course'];

                    // redirect based on role
                    if ($user['role'] === 'super_admin') {
                        header("Location: ../public/superadmin/Dashboard.php");
                    } elseif ($user['role'] === 'admin') {
                        header("Location: ../public/admin/Dashboard.php");
                    } else {
                        header("Location: ../public/user/Dashboard.php");
                    }
                    exit();
                }

                $error = "Incorrect password";
            } else {
                $error = "User not found";
            }
        }

        return $error;
    }

    public function register(array $input, $request_method) {
        $errors = [];

        if ($request_method === "POST" && isset($input['register'])) {
            $name = trim($input['name'] ?? '');
            $email = trim($input['email'] ?? '');
            $student_id = trim($input['student_id'] ?? '');
            $course = trim($input['course'] ?? '');
            $password = $input['password'] ?? '';
            $confirm_password = $input['confirm_password'] ?? '';

            if (empty($name)) {
                $errors[] = "Full name is required";
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
                $errors[] = "Name can only contain letters and spaces";
            }

            if (empty($email)) {
                $errors[] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            if (empty($student_id)) {
                $errors[] = "Student ID is required";
            } elseif (!preg_match("/^[0-9]{4}-[0-9]{5}$/", $student_id)) {
                $errors[] = "Student ID must follow format: 2021-12345";
            }

            if (empty($course)) {
                $errors[] = "Please select a course";
            }

            if (empty($password)) {
                $errors[] = "Password is required";
            } else {
                if (strlen($password) < 6) {
                    $errors[] = "Password must be at least 6 characters";
                }
                if (!preg_match('/[A-Z]/', $password)) {
                    $errors[] = "Password must contain at least one uppercase letter";
                }
                if (!preg_match('/[0-9]/', $password)) {
                    $errors[] = "Password must contain at least one number";
                }
                if (strpos($password, ' ') !== false) {
                    $errors[] = "Password cannot contain spaces";
                }
            }

            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match";
            }

            if (empty($errors) && $this->model->existsByEmailOrStudentId($email, $student_id)) {
                $errors[] = "Email or Student ID already exists";
            }

            if (empty($errors)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                if ($this->model->createStudent($name, $email, $student_id, $course, $hashed_password)) {
                    header("Location: Login.php?registered=success");
                    exit();
                } else {
                    $errors[] = "Registration failed. Please try again.";
                }
            }
        }

        return $errors;
    }
}
