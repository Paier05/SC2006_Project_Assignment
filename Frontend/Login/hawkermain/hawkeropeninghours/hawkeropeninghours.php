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

    // Check current opening status of the stall
    $current_time = date('h:iA'); // Format: "09:00AM" or "11:30PM"
    $opening_status = ($current_time >= $opening_time && $current_time <= $closing_time) ? 1 : 0;

    // Update the opening hours in the database
    $query = "UPDATE HawkerStalls
              SET opening_time = ?, closing_time = ?, opening_status = ?
              WHERE user_id = ?";
    
    $params = array($opening_time, $closing_time, $opening_status, $user_id, $days);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    echo 'Opening hours updated successfully.';
}
?>

