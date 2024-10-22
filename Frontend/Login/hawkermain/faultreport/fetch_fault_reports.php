<?php
session_start();
include '../config.php';

// Check if user is logged in and user_id is set in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Retrieve the user_id from the session
} else {
    die("User not logged in.");
}

// Query to fetch fault reports for the specific user_id
$sql = "SELECT fault_reports FROM HawkerStalls WHERE id = 4";  //error retrieving id from $user_id = $_SESSION['user_id']
$params = array($user_id); // Use a parameterized query to prevent SQL injection

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Output the fault reports as HTML
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo '<div class="report-item">' . htmlspecialchars($row['fault_reports']) . '</div>';
}

// Free statement and close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
