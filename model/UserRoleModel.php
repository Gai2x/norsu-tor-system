<?php

class UserRoleModel
{
    public const ROLE_USER = 'student';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPER_ADMIN = 'super_admin';

    public static function all(): array
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }

    public static function can(string $role, string $permission): bool
    {
        $permissions = [
            self::ROLE_USER => [
                'view_own_dashboard',
                'manage_own_requests',
                'manage_own_appointments',
            ],
            self::ROLE_ADMIN => [
                'view_admin_dashboard',
                'manage_requests',
                'manage_appointments',
                'view_students',
            ],
            self::ROLE_SUPER_ADMIN => [
                'view_super_admin_dashboard',
                'manage_admins',
                'view_all_users',
                'view_all_requests',
                'view_all_appointments',
                'override_admin_actions',
            ],
        ];

        return in_array($permission, $permissions[$role] ?? [], true);
    }

    public static function requireRole(string $role, string $redirect = '../Login.php'): void
    {
        self::requireAnyRole([$role], $redirect);
    }

    public static function requireAnyRole(array $roles, string $redirect = '../Login.php'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!in_array($_SESSION['role'] ?? null, $roles, true)) {
            header("Location: {$redirect}");
            exit();
        }
    }
}
