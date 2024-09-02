<?php
require 'config.php';

function uploadFile($file, $pdo)
{
    // Check if the file is provided and no upload error occurred
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return json_encode(['status' => 'error', 'message' => 'No file uploaded or an error occurred during the upload.']);
    }

    // Check file size (limit: 1MB)
    if ($file['size'] > 1 * 1024 * 1024) {
        return json_encode(['status' => 'error', 'message' => 'File size should not exceed 1MB.']);
    }

    // Allowed file types
    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain'
    ];

    if (!in_array($file['type'], $allowedTypes)) {
        return json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
    }

    // Sanitize the file name and prepare the upload directory
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Get the file extension and sanitize the file name
    $fileInfo = pathinfo($file['name']);
    $fileName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileInfo['filename']) . '.' . strtolower($fileInfo['extension']);
    $filePath = $uploadDir . $fileName;

    // Move the uploaded file to the server
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        return json_encode(['status' => 'error', 'message' => 'Failed to upload the file.']);
    }

    // Insert file information into the database
    $sql = "INSERT INTO uploads (file_name, file_type, file_size, file_path, upload_date) VALUES (:file_name, :file_type, :file_size, :file_path, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':file_name', $fileName);
    $stmt->bindParam(':file_type', $file['type']);
    $stmt->bindParam(':file_size', $file['size']);
    $stmt->bindParam(':file_path', $filePath);

    if ($stmt->execute()) {
        return json_encode(['status' => 'success', 'message' => 'File uploaded successfully!']);
    } else {
        return json_encode(['status' => 'error', 'message' => 'Failed to save file information to the database.']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'file-upload') {
    try {
        echo uploadFile($_FILES['file_input'], $pdo);
    } catch (Exception $e) {
        error_log($e->getMessage()); // Log the error
        echo json_encode(['status' => 'error', 'message' => 'An internal error occurred.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method or action.']);
}
