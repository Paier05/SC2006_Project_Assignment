<?php
session_start();

// Disable error reporting to prevent non-JSON output
error_reporting(0);

header('Content-Type: application/json');

if (!isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'message' => 'No image file provided.']);
    exit;
}

// Use absolute path for the uploads directory
$uploadsDir = '../../hawkerinitialize/uploads/';
$imageFile = $_FILES['image'];
$imageName = basename($imageFile['name']);
$targetFilePath = $uploadsDir . $imageName;

if (@move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {
    echo json_encode(['success' => true, 'filename' => $imageName]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
}
?>
