<?php
session_start();
include '../config.php'; // Include your database configuration file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$stall_owner = $_SESSION['user_id'];

// Check if fault_report content is provided
if (isset($_POST['fault_report'])) {
    $fault_report = $_POST['fault_report'];

    // Delete the fault report with the specific content for the logged-in stall owner
    $query = "DELETE FROM faultReport WHERE fault_report = ? AND stall_owner = ?";
    $params = array($fault_report, $stall_owner);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Return a success message
    echo 'success';

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    echo 'error';
}
?>
