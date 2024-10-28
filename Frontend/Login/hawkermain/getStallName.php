<?php
session_start();
include '../config.php'; // Include database connection

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $stall_owner = $_SESSION['user_id'];
} else {
    die("User not logged in.");
}

// Query to fetch the stall name for the logged-in user
$query = "SELECT stall_name FROM HawkerStalls WHERE stall_owner = ?";
$params = array($stall_owner);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch and output the stall name as JSON
if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo json_encode(array('stall_name' => $row['stall_name']));
} else {
    echo json_encode(array('error' => 'Stall name not found.'));
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
