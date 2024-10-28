<?php
session_start();
include '../config.php'; // Include your database configuration file

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $stall_owner = $_SESSION['user_id'];
} else {
    die("User not logged in.");
}

// Fetch fault reports for the logged-in stall owner
$query = "SELECT fault_report FROM faultReport WHERE stall_owner = ?";
$params = array($stall_owner);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Output fault reports with a delete button
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $fault_report_text = htmlspecialchars($row['fault_report']);
    echo '<div class="report-item">';
    echo '<p>' . $fault_report_text . '</p>';
    echo '<button class="delete-btn" onclick="deleteReport(`' . addslashes($fault_report_text) . '`)">X</button>';
    echo '</div>';
}

// Free the statement and close the connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
