<?php

require_once __DIR__ . '/../../model/user/RequestModel.php';

class RequestController {
    private $model;

    public function __construct($database_connection) {
        $this->model = new RequestModel($database_connection);
    }

    public function submitRequest($user_id, $service_type, $notes, $year_level, $contact_number, $file) {
        $success_message = '';
        $error_message = '';

        if (empty($service_type)) {
            $error_message = "Please select a service type.";
            return ['success' => $success_message, 'error' => $error_message];
        }

        $uploaded_file = '';
        if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
            $allowed_types = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $max_size = 10 * 1024 * 1024;

            if (in_array($file_ext, $allowed_types, true) && $file['size'] <= $max_size) {
                $upload_dir = dirname(__DIR__, 2) . '/uploads/requests/';
                if (!is_dir($upload_dir) && !mkdir($upload_dir, 0775, true)) {
                    $error_message = "Upload folder is not available. Please try again.";
                    return ['success' => $success_message, 'error' => $error_message];
                }

                $original_name = pathinfo($file['name'], PATHINFO_FILENAME);
                $safe_name = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $original_name);
                $safe_name = trim($safe_name, '_');
                $safe_name = substr($safe_name ?: 'document', 0, 80);
                $random = bin2hex(random_bytes(8));
                $filename = time() . '_' . $user_id . '_' . $random . '_' . $safe_name . '.' . $file_ext;
                $upload_path = $upload_dir . $filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $uploaded_file = 'uploads/requests/' . $filename;
                } else {
                    $error_message = "Failed to upload file. Please try again.";
                    return ['success' => $success_message, 'error' => $error_message];
                }
            } else {
                $error_message = "Invalid file. Please upload JPG, PNG, PDF, DOC, or DOCX files under 10MB.";
                return ['success' => $success_message, 'error' => $error_message];
            }
        } elseif (isset($file) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $error_message = "File upload failed. Please try again.";
            return ['success' => $success_message, 'error' => $error_message];
        }

        if ($this->model->createRequest($user_id, $service_type, $notes, $year_level, $contact_number, $uploaded_file)) {
            $success_message = "Your request has been submitted successfully!";
        } else {
            $error_message = "Failed to submit request. Please try again.";
        }

        return ['success' => $success_message, 'error' => $error_message];
    }

    public function cancelRequest($request_id, $user_id) {
        $success_message = '';
        $error_message = '';

        if ($this->model->cancelPendingRequest($request_id, $user_id)) {
            $success_message = "Request cancelled successfully.";
        } else {
            $error_message = "Unable to cancel request. Only pending requests can be cancelled.";
        }

        return ['success' => $success_message, 'error' => $error_message];
    }

    public function getUserRequests($user_id) {
        return $this->model->getUserRequests($user_id);
    }

    public function getStatusCounts($requests) {
        $status_counts = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'cancelled' => 0
        ];
        
        foreach($requests as $request) {
            if(isset($status_counts[$request['status']])) {
                $status_counts[$request['status']]++;
            }
        }
        
        return $status_counts;
    }

    public function getUserDetails($user_id) {
        return $this->model->getUserDetails($user_id);
    }
}
