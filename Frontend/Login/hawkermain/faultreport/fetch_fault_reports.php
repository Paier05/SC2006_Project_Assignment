<?php
session_start();
include '../config.php';

if (isset($_SESSION['user_id'])) {
    $stall_owner = $_SESSION['user_id'];
} else {
    die("User not logged in.");
}

$query = "SELECT fault_report FROM faultReport WHERE stall_owner = ?";
$params = array($stall_owner);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $fault_report_text = htmlspecialchars($row['fault_report'], ENT_QUOTES, 'UTF-8');
    echo '<div class="report-item">';
    echo '<p>' . $fault_report_text . '</p>';
    echo '<button class="delete-btn" onclick="deleteReport(`' . addslashes($fault_report_text) . '`)">Delete</button>';
    echo '</div>';
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
