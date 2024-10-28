<?php
include '../config.php';

// Get the hawker center ID from the request
$hawkerCenterId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Return an error if no valid ID is provided
if ($hawkerCenterId === 0) {
    echo json_encode(['error' => 'Invalid Hawker Center ID']);
    exit;
}

// Query to fetch stalls for the specified hawker center
$sql = "SELECT id, stall_name, opening_hours, opening_days, sum_rating, total_number_of_rating FROM HawkerStalls WHERE hawker_center_id = ?";
/*
$sql = "
    SELECT
        HS.id AS StallID,
        HS.stall_name,
        HS.opening_hours,
    FROM HawkerStalls HS
    WHERE HS.hawker_center_id = ?
";
*/
$params = array($hawkerCenterId);
$stmt = sqlsrv_query($conn, $sql, $params);

$stalls = [];

if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $stalls[] = $row;
    }
}

// Close connection
sqlsrv_close($conn);

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($stalls);
?>
