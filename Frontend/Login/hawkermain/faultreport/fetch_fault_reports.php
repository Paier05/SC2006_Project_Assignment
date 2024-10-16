<?php
session_start();
include 'config.php';
// Query to fetch fault reports
$sql = "SELECT fault_report FROM fault_reports"; // Assuming the table is 'fault_reports' and the column is 'fault_report'
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Output the fault reports as HTML
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo '<div class="report-item">' . htmlspecialchars($row['fault_report']) . '</div>';
}

// Free statement and close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
