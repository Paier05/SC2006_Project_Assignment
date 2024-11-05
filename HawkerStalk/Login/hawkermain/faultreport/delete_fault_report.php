<?php
session_start();
include '../../config.php';

header('Content-Type: text/plain'); // Ensure plain text response for AJAX

if (!isset($_SESSION['user_id'])) {
    echo 'error';
    exit;
}

$stall_owner = $_SESSION['user_id'];

// First, query to get the hawker_stall_id based on the stall_owner
$hawker_stall_query = "SELECT id FROM HawkerStalls WHERE stall_owner = ?";
$params = array($stall_owner); // Use stall_owner from session
$hawker_stall_stmt = sqlsrv_query($conn, $hawker_stall_query, $params);

if ($hawker_stall_stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$stall_owner = null;
if ($row = sqlsrv_fetch_array($hawker_stall_stmt, SQLSRV_FETCH_ASSOC)) {
    $stall_owner = $row['id'];
} else {
    die("No hawker stall found for the logged-in user.");
}

if (isset($_POST['fault_report'])) {
    $fault_report = $_POST['fault_report'];

    $query = "DELETE FROM faultReport WHERE fault_report = ? AND stall_owner = ?";
    $params = array($fault_report, $stall_owner);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        echo 'error';
        die(print_r(sqlsrv_errors(), true));
    }

    echo 'success';

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    echo 'error';
}
?>
