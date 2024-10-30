<?php
include '../config.php';

// Get the stall ID from the request
$stallId = isset($_GET['stall_id']) ? (int)$_GET['stall_id'] : 0;

if ($stallId!=0) {
    // Fetch stall details
    $sql = "SELECT * FROM HawkerStalls WHERE id = ?";
    $params = array($stallId);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $stall = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

} else {
    echo "Invalid stall ID.";
    exit;
}
?>