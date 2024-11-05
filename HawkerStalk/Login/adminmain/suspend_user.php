<?php
include '../config.php'; // This should include your connection code

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $query = "UPDATE users SET status = 'suspended' WHERE user_id = ?";
    $params = array($userId);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: user_management.php"); // Redirect back to the dashboard
    exit;
}
?>
