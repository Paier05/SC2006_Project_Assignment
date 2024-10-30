<?php
include '../../config.php';

// Get the stall ID from the request
$stallId = isset($_GET['stall_id']) ? (int)$_GET['stall_id'] : 0;

if ($stallId === 0) {
    echo json_encode(['error' => 'Invalid Stall ID']);
    exit;
}

// Query to fetch the menu items for the specified stall
$sql = "SELECT id, stall_name, opening_hours, opening_days, sum_rating, total_number_of_rating FROM HawkerStalls WHERE id = ?";
$params = array($stallId);
$stmt = sqlsrv_query($conn, $sql, $params);

$stallInfo;

if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $stallInfo = $row;
    }
}

// Close connection
sqlsrv_close($conn);

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($stallInfo);
?>
