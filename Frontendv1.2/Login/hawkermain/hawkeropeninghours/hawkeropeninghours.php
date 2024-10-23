<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include your Azure SQL connection details
    include 'config.php'; // Assume config.php includes the $conn variable for SQL Server

    $days = $_POST['days'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Save the opening hours to the database
    $query = "INSERT INTO opening_hours (days, start_time, end_time) VALUES (?, ?, ?)";
    $params = array($days, $startTime, $endTime);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    echo 'Opening hours saved successfully.';
}
?>
