<?php
session_start();
include '../config.php'; // Include your database connection

// Check if the hawker is logged in
if (isset($_SESSION['user_id'])) {
    $hawker_stall_id = $_SESSION['user_id']; // This is the hawker's user_id
} else {
    die("User not logged in.");
}

// Query to fetch ratings and reviews for the specific hawker stall
$sql = "SELECT rating, review, user_id FROM StallRatings WHERE hawker_stall_id = 4 AND id = 4";
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
    echo '<p><strong>Reviewed by Customer ID: ' . htmlspecialchars($row['user_id']) . '</strong></p>';
    echo '</div>';
}

// Free statement and close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
