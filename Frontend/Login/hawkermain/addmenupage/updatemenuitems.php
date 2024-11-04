<?php
session_start();
include '../config.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

// Check if menuItems data is provided
if (!isset($data['menuItems']) || !is_array($data['menuItems'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data format.']);
    exit;
}

// Retrieve StallID for the logged-in user
$stallQuery = "SELECT id FROM HawkerStalls WHERE stall_owner = ?";
$stallParams = array($user_id);
$stallStmt = sqlsrv_query($conn, $stallQuery, $stallParams);

if ($stallStmt === false || !sqlsrv_fetch($stallStmt)) {
    echo json_encode(['success' => false, 'message' => 'Error retrieving stall ID.']);
    exit;
}

$stallId = sqlsrv_get_field($stallStmt, 0);
sqlsrv_free_stmt($stallStmt);

// Update or insert each menu item in the StallMenus table
foreach ($data['menuItems'] as $item) {
    if (empty($item['MenuItemID'])) {
        // Insert new menu item
        $insertQuery = "INSERT INTO StallMenus (StallID, ItemName, ItemDescription, ItemImage, Price) VALUES (?, ?, ?, ?, ?)";
        $insertParams = array($stallId, $item['ItemName'], $item['ItemDescription'], $item['ItemImage'], $item['Price']);
        $stmt = sqlsrv_query($conn, $insertQuery, $insertParams);
    } else {
        // Update existing menu item
        $updateQuery = "UPDATE StallMenus SET ItemName = ?, ItemDescription = ?, ItemImage = ?, Price = ? WHERE MenuItemID = ? AND StallID = ?";
        $updateParams = array($item['ItemName'], $item['ItemDescription'], $item['ItemImage'], $item['Price'], $item['MenuItemID'], $stallId);
        $stmt = sqlsrv_query($conn, $updateQuery, $updateParams);
    }

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
        exit;
    }
    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
echo json_encode(['success' => true]);
?>