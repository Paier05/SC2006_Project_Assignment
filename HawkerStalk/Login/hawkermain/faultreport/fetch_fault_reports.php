<?php
session_start();
include '../../config.php';

if (isset($_SESSION['user_id'])) {
    $stall_owner = $_SESSION['user_id'];
} else {
    die("User not logged in.");
}

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

sqlsrv_free_stmt($hawker_stall_stmt); // Free the statement resource

$query = "SELECT fault_report FROM faultReport WHERE stall_owner = ?";
$params = array($stall_owner);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$hasFaultReports = false;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $fault_report_text = htmlspecialchars($row['fault_report'], ENT_QUOTES, 'UTF-8');
    $hasFaultReports = true;
    echo '<div class="report-item">';
    echo '<p>' . $fault_report_text . '</p>';
    echo '<button class="delete-btn" onclick="deleteReport(`' . addslashes($fault_report_text) . '`)">Solved</button>';
    echo '</div>';
}

if (!$hasFaultReports) {
    // If no fault reports found, display the message
    echo '<p>Your stall does not have any fault reports at the moment.</p>';
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
