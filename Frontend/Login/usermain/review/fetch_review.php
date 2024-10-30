<?php
include '../../config.php';

// Get the stall ID from the request
$stallId = isset($_GET['stall_id']) ? (int)$_GET['stall_id'] : 0;

if ($stallId === 0) {
    echo json_encode(['error' => 'Invalid Stall ID']);
    exit;
}

// Query to fetch the menu items for the specified stall
$sql = "SELECT rating, review, user_email FROM StallRatings WHERE hawker_stall_id = ?";
$params = array($stallId);
$stmt = sqlsrv_query($conn, $sql, $params);

$reviews = [];

if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $reviews[] = $row;
    }
}

// Close connection
sqlsrv_close($conn);

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($reviews);
?>
