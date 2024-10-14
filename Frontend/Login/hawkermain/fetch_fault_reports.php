<?php
// Database connection parameters
$serverName = "#";
$connectionOptions = array(
    "Database" => "#",
    "Uid" => "#", //userID
    "PWD" => "#" //password
);

// Establishes the connection to Azure SQL
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Query to fetch fault reports (without user ID)
$sql = "SELECT fault_report FROM fault_reports"; // Assuming table is named 'fault_reports' and the fault report is in the 'fault_report' column
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// HTML to display the fault reports
echo "<h2>Fault Reports</h2>";
echo "<ul>";

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<li>" . htmlspecialchars($row['fault_report']) . "</li>";
}

echo "</ul>";

// Free statement and close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
