<?php

require_once __DIR__ . '/../../database/Connection.php';
require_once __DIR__ . '/../../model/UserRoleModel.php';

class SuperAdminPageController
{
    public static function boot(string $pageTitle, string $pageSubtitle, array $stats = [], string $currentPageKey = ''): array
    {
        UserRoleModel::requireRole(UserRoleModel::ROLE_SUPER_ADMIN, '/Norsu_Tor/public/Login.php');

        return [
            'conn' => $GLOBALS['conn'] ?? null,
            'pageTitle' => $pageTitle,
            'pageSubtitle' => $pageSubtitle,
            'currentPageKey' => $currentPageKey,
            'stats' => array_merge([
                'pending_requests' => 0,
                'pending_appointments' => 0,
            ], $stats),
        ];
    }
}
