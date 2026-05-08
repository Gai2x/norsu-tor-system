<?php

require_once __DIR__ . '/../../database/connection.php';
require_once __DIR__ . '/../../model/UserRoleModel.php';

class AdminPageController
{
    public static function boot($pageTitle, $pageSubtitle, array $stats = [], string $currentPageKey = '')
    {
        UserRoleModel::requireAnyRole(
            [UserRoleModel::ROLE_SUPER_ADMIN, UserRoleModel::ROLE_ADMIN],
            '../Login.php'
        );

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
