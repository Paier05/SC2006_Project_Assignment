<?php
session_start();
include '../config.php';

header('Content-Type: text/plain'); // Ensure plain text response for AJAX

if (!isset($_SESSION['user_id'])) {
    echo 'error';
    exit;
}

$stall_owner = $_SESSION['user_id'];

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
