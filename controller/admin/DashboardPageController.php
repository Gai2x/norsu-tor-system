<?php

require_once __DIR__ . '/AdminPageController.php';
require_once __DIR__ . '/AdminController.php';

class DashboardPageController{
    public static function load(){
        $viewData = AdminPageController::boot(
            'Dashboard',
            'Overview of System Activity',
            [],
            'dashboard'
        );

        $conn = $viewData['conn'];
        AdminController::updateRequestStatus($conn);
        $dashboardData = AdminController::getDashboardData($conn);

        $totalRequests = 0;
        $pendingRequests = 0;
        $approvedRequests = 0;
        $rejectedRequests = 0;

        $totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM requests");
        if ($totalQuery) {
            $totalRequests = mysqli_fetch_assoc($totalQuery)['total'];
        }
        $oneTimeTotalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM one_time_requests");
        if ($oneTimeTotalQuery) {
            $totalRequests += (int) (mysqli_fetch_assoc($oneTimeTotalQuery)['total'] ?? 0);
        }

        $pendingQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE status='pending'");
        if ($pendingQuery) {
            $pendingRequests = mysqli_fetch_assoc($pendingQuery)['total'];
        }
        $oneTimePendingQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM one_time_requests WHERE status='pending'");
        if ($oneTimePendingQuery) {
            $pendingRequests += (int) (mysqli_fetch_assoc($oneTimePendingQuery)['total'] ?? 0);
        }

        $approvedQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE status='approved'");
        if ($approvedQuery) {
            $approvedRequests = mysqli_fetch_assoc($approvedQuery)['total'];
        }
        $oneTimeApprovedQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM one_time_requests WHERE status='approved'");
        if ($oneTimeApprovedQuery) {
            $approvedRequests += (int) (mysqli_fetch_assoc($oneTimeApprovedQuery)['total'] ?? 0);
        }

        $rejectedQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM requests WHERE status='rejected'");
        if ($rejectedQuery) {
            $rejectedRequests = mysqli_fetch_assoc($rejectedQuery)['total'];
        }
        $oneTimeRejectedQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM one_time_requests WHERE status='rejected'");
        if ($oneTimeRejectedQuery) {
            $rejectedRequests += (int) (mysqli_fetch_assoc($oneTimeRejectedQuery)['total'] ?? 0);
        }

        $recentOneTimeRequests = [];
        $recentOneTimeQuery = mysqli_query($conn, "
            SELECT
                otr.id,
                otr.student_id,
                otr.fullname AS student_name,
                otr.service_type,
                otr.status,
                otr.created_at,
                'one_time' AS type
            FROM one_time_requests otr
            ORDER BY otr.created_at DESC
            LIMIT 5
        ");
        if ($recentOneTimeQuery) {
            $recentOneTimeRequests = mysqli_fetch_all($recentOneTimeQuery, MYSQLI_ASSOC);
        }

        $recentRegularRequests = [];
        if (isset($dashboardData['recentRequests']) && is_array($dashboardData['recentRequests'])) {
            foreach ($dashboardData['recentRequests'] as $regularRequest) {
                $regularRequest['type'] = 'regular';
                if (!isset($regularRequest['student_name']) && isset($regularRequest['name'])) {
                    $regularRequest['student_name'] = $regularRequest['name'];
                }
                $recentRegularRequests[] = $regularRequest;
            }
        }

        $mergedRecentRequests = array_merge($recentRegularRequests, $recentOneTimeRequests);
        usort($mergedRecentRequests, function ($a, $b) {
            $left = strtotime($a['created_at'] ?? '1970-01-01 00:00:00');
            $right = strtotime($b['created_at'] ?? '1970-01-01 00:00:00');
            return $right <=> $left;
        });
        $dashboardData['recentRequests'] = array_slice($mergedRecentRequests, 0, 5);

        $requests = mysqli_query($conn, "
            SELECT * FROM (
                SELECT
                    r.id,
                    COALESCE(s.name, 'N/A') AS name,
                    COALESCE(s.student_id, 'N/A') AS student_id,
                    r.service_type,
                    r.status,
                    r.created_at,
                    'regular' AS type
                FROM requests r
                LEFT JOIN students s ON r.user_id = s.id

                UNION ALL

                SELECT
                    otr.id,
                    COALESCE(otr.fullname, 'N/A') AS name,
                    COALESCE(otr.student_id, 'N/A') AS student_id,
                    otr.service_type,
                    otr.status,
                    otr.created_at,
                    'one_time' AS type
                FROM one_time_requests otr
            ) AS all_requests
            ORDER BY created_at DESC, id DESC
        ");

        $viewData['stats']['pending_requests'] = (int) $pendingRequests;

        return array_merge($viewData, $dashboardData, [
            'totalRequests' => $totalRequests,
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
            'recentOneTimeRequests' => $recentOneTimeRequests,
            'requests' => $requests,
        ]);
    }
}
