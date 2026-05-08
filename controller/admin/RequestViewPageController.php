<?php

require_once __DIR__ . '/AdminPageController.php';

class RequestViewPageController
{
    public static function load()
    {
        $viewData = AdminPageController::boot(
            'Request Details',
            'View and manage request information'
        );

        $conn = $viewData['conn'];

        if (!isset($_GET['id'])) {
            die("Request ID missing.");
        }

        $id = intval($_GET['id']);

        $query = mysqli_query($conn, "
            SELECT r.*, s.name, s.student_id, s.course
            FROM requests r
            LEFT JOIN students s ON r.user_id = s.id
            WHERE r.id = $id
        ");

        $request = mysqli_fetch_assoc($query);

        if (!$request) {
            die("Request not found.");
        }

        if (!empty($request['document_file'])) {
            $normalizedFile = self::normalizeDocumentFilePath($request['document_file']);

            if ($normalizedFile !== $request['document_file']) {
                $stmt = $conn->prepare("UPDATE requests SET document_file = ? WHERE id = ?");
                $stmt->bind_param("si", $normalizedFile, $id);
                $stmt->execute();
                $stmt->close();
                $request['document_file'] = $normalizedFile;
            }
        }

        return array_merge($viewData, [
            'request' => $request,
        ]);
    }

    private static function normalizeDocumentFilePath($path)
    {
        $path = trim(str_replace('\\', '/', $path));
        $path = ltrim($path, '/');

        if (strpos($path, 'public/uploads/requests/') === 0) {
            return 'uploads/requests/' . basename($path);
        }

        return $path;
    }
}
