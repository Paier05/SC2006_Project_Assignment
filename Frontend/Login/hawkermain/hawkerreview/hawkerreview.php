<?php
session_start();
include '../../config.php'; // Include database connection

// Check if the hawker is logged in
if (isset($_SESSION['user_id'])) {
    $stall_owner = $_SESSION['user_id']; // This is the hawker's user_id
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

$hawker_stall_id = null;
if ($row = sqlsrv_fetch_array($hawker_stall_stmt, SQLSRV_FETCH_ASSOC)) {
    $hawker_stall_id = $row['id'];
} else {
    die("No hawker stall found for the logged-in user.");
}

sqlsrv_free_stmt($hawker_stall_stmt); // Free the statement resource

// Query to fetch ratings and reviews for the specific hawker stall
$sql = "SELECT rating, review, user_email FROM StallRatings WHERE hawker_stall_id = ?";
$params = array($hawker_stall_id); // Prevent SQL injection

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Output the ratings and reviews as HTML
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo '<div class="review-item">';
    echo '<h3>Rating: <span class="rating">' . htmlspecialchars($row['rating']) . ' / 5</span></h3>';
    echo '<p>' . htmlspecialchars($row['review']) . '</p>';
    echo '<p><strong>Reviewed by Customer ID: ' . htmlspecialchars($row['user_email']) . '</strong></p>';
    echo '</div>';
}

// Free statement and close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
