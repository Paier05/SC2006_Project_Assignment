<?php
// Start session to access session variables
session_start();
include '../config.php'; // Your DB connection

// Check if the user is logged in by verifying if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit;
}

$hawker_id = $_SESSION['user_id']; // Assuming hawker is logged in

// Fetch menu items if it's a GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Query to retrieve menu items for the logged-in hawker
    $query = "SELECT * FROM StallMenus WHERE HawkerID = ?";
    $params = array($hawker_id);
    $stmt = sqlsrv_query($conn, $query, $params);

    $menu_items = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $menu_items[] = $row;
    }

    // Return the menu items as JSON for the front-end to process
    header('Content-Type: application/json');
    echo json_encode($menu_items);

    sqlsrv_free_stmt($stmt); // Free resources
    exit; // Stop further execution since it's a GET request
}

// Save or update menu items if it's a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_items = json_decode(file_get_contents('php://input'), true);

    if (!$menu_items) {
        echo json_encode(['status' => 'error', 'message' => 'No data provided']);
        exit;
    }

    // Loop through each item and either update or insert
    foreach ($menu_items as $item) {
        // Check if this is an existing item (menuItemID exists) or a new one
        if (!empty($item['MenuItemID'])) {
            // Update existing menu item
            $query = "UPDATE StallMenus 
                      SET ItemName = ?, ItemDescription = ?, Price = ? 
                      WHERE MenuItemID = ? AND HawkerID = ?";
            $params = array($item['ItemName'], $item['ItemDescription'], $item['Price'], $item['MenuItemID'], $hawker_id);
            sqlsrv_query($conn, $query, $params);
        } else {
            // Insert new menu item
            $query = "INSERT INTO StallMenus (HawkerID, ItemName, ItemDescription, Price) 
                      VALUES (?, ?, ?, ?)";
            $params = array($hawker_id, $item['ItemName'], $item['ItemDescription'], $item['Price']);
            sqlsrv_query($conn, $query, $params);
        }
    }

    // Respond with a success message
    echo json_encode(['status' => 'success']);
}
?>