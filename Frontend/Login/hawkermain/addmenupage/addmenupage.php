<?php
// Start session
session_start();
include '../config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve from input
    $days = $_POST['days'];
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];

    // Get the logged-in user's ID from session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Assume user_id is stored in session
    } else {
        die("User not logged in.");
    }

    // Update the menu item in the database
    $query = "UPDATE StallMenus
              SET ItemName = ?, ItemDescription = ?, ItemImage = ?, Price = ?
              WHERE HawkerID = ?";
    
    $params = array($ItemName, $ItemDescription, $ItemImage, $Price);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

}
?>

