<?php

require_once __DIR__ . '/../../database/Connection.php';

class UserLayoutController
{
    public static function buildHeaderData($conn)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $userHeader = null;

        if ($userId) {
            $userHeaderStmt = $conn->prepare("SELECT profile_image, name, course FROM students WHERE id = ?");
            $userHeaderStmt->bind_param("i", $userId);
            $userHeaderStmt->execute();
            $userHeaderResult = $userHeaderStmt->get_result();
            $userHeader = $userHeaderResult->fetch_assoc();
            $userHeaderStmt->close();
        }

        return $userHeader;
    }
}
