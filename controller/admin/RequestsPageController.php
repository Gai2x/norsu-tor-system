<?php

require_once __DIR__ . '/AdminPageController.php';
require_once __DIR__ . '/AdminController.php';

class RequestsPageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Requests',
            'Manage Student Requests',
            [],
            'requests'
        );

        $conn = $viewData['conn'];
        AdminController::updateRequestStatus($conn);
        $search = trim($_GET['search'] ?? '');

        $regularRequests = [];
        if ($search !== '') {
            $like = '%' . $search . '%';
            $regularRequestsQuery = mysqli_prepare($conn, "
                SELECT r.*, s.name, s.student_id, s.course
                FROM requests r
                LEFT JOIN students s ON r.user_id = s.id
                WHERE COALESCE(s.name, '') LIKE ?
                    OR COALESCE(r.service_type, '') LIKE ?
                    OR COALESCE(r.status, '') LIKE ?
                    OR 'regular request' LIKE ?
                    OR 'regular' LIKE ?
                ORDER BY r.created_at DESC, r.id DESC
            ");
            mysqli_stmt_bind_param($regularRequestsQuery, 'sssss', $like, $like, $like, $like, $like);
            mysqli_stmt_execute($regularRequestsQuery);
            $regularResult = mysqli_stmt_get_result($regularRequestsQuery);
        } else {
            $regularResult = mysqli_query($conn, "
                SELECT r.*, s.name, s.student_id, s.course
                FROM requests r
                LEFT JOIN students s ON r.user_id = s.id
                ORDER BY r.created_at DESC, r.id DESC
            ");
        }
        if ($regularResult) {
            while ($row = mysqli_fetch_assoc($regularResult)) {
                $regularRequests[] = [
                    'id' => $row['id'],
                    'name' => $row['name'] ?? 'N/A',
                    'fullname' => $row['name'] ?? 'N/A',
                    'student_id' => $row['student_id'] ?? 'N/A',
                    'service_type' => $row['service_type'] ?? 'N/A',
                    'status' => $row['status'] ?? 'pending',
                    'created_at' => $row['created_at'] ?? null,
                    'type' => 'regular',
                ];
            }
        }
        if (isset($regularRequestsQuery) && $regularRequestsQuery instanceof mysqli_stmt) {
            mysqli_stmt_close($regularRequestsQuery);
        }

        $oneTimeRequests = [];
        if ($search !== '') {
            $like = '%' . $search . '%';
            $oneTimeRequestsQuery = mysqli_prepare($conn, "
                SELECT id, student_id, fullname, service_type, status, created_at
                FROM one_time_requests
                WHERE COALESCE(fullname, '') LIKE ?
                    OR COALESCE(service_type, '') LIKE ?
                    OR COALESCE(status, '') LIKE ?
                    OR 'one-time request' LIKE ?
                    OR 'one time request' LIKE ?
                    OR 'one_time' LIKE ?
                ORDER BY created_at DESC, id DESC
            ");
            mysqli_stmt_bind_param($oneTimeRequestsQuery, 'ssssss', $like, $like, $like, $like, $like, $like);
            mysqli_stmt_execute($oneTimeRequestsQuery);
            $oneTimeResult = mysqli_stmt_get_result($oneTimeRequestsQuery);
        } else {
            $oneTimeResult = mysqli_query($conn, "
                SELECT id, student_id, fullname, service_type, status, created_at
                FROM one_time_requests
                ORDER BY created_at DESC, id DESC
            ");
        }
        if ($oneTimeResult) {
            while ($row = mysqli_fetch_assoc($oneTimeResult)) {
                $oneTimeRequests[] = [
                    'id' => $row['id'],
                    'name' => $row['fullname'] ?? 'N/A',
                    'fullname' => $row['fullname'] ?? 'N/A',
                    'student_id' => $row['student_id'] ?? 'N/A',
                    'service_type' => $row['service_type'] ?? 'N/A',
                    'status' => $row['status'] ?? 'pending',
                    'created_at' => $row['created_at'] ?? null,
                    'type' => 'one_time',
                ];
            }
        }
        if (isset($oneTimeRequestsQuery) && $oneTimeRequestsQuery instanceof mysqli_stmt) {
            mysqli_stmt_close($oneTimeRequestsQuery);
        }

        $allRequests = array_merge($regularRequests, $oneTimeRequests);
        usort($allRequests, function ($a, $b) {
            $left = strtotime($a['created_at'] ?? '1970-01-01 00:00:00');
            $right = strtotime($b['created_at'] ?? '1970-01-01 00:00:00');
            return $right <=> $left;
        });

        $pendingCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM requests WHERE status = 'pending'");
        $pendingTotal = 0;
        if ($pendingCountResult) {
            $pendingTotal += (int) (mysqli_fetch_assoc($pendingCountResult)['total'] ?? 0);
        }
        $oneTimePendingResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM one_time_requests WHERE status = 'pending'");
        if ($oneTimePendingResult) {
            $pendingTotal += (int) (mysqli_fetch_assoc($oneTimePendingResult)['total'] ?? 0);
        }
        $viewData['stats']['pending_requests'] = $pendingTotal;

        return array_merge($viewData, [
            'allRequests' => $allRequests,
            'requests' => $allRequests,
            'search' => $search,
        ]);
    }
}
