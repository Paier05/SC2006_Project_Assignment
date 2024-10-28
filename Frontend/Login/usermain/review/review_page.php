<?php
include '../config.php';

// Get the stall ID from the request
$stallId = isset($_GET['stall_id']) ? (int)$_GET['stall_id'] : 0;

if ($stallId!=0) {
    // Fetch stall details
    $sql = "SELECT * FROM HawkerStalls WHERE id = ?";
    $params = array($stallId);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $stall = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Fetch existing reviews
    $reviewSql = "SELECT * FROM StallRatings WHERE hawker_stall_id = ?";
    $reviewStmt = sqlsrv_query($conn, $reviewSql, $params);
    $reviews = [];
    while ($row = sqlsrv_fetch_array($reviewStmt, SQLSRV_FETCH_ASSOC)) {
        $reviews[] = $row;
    }
} else {
    echo "Invalid stall ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review for <?php echo htmlspecialchars($stall['stall_name']); ?></title>
</head>
<body>
    <h1>Review for <?php echo htmlspecialchars($stall['stall_name']); ?></h1>
    <p>Opening Hours: <?php echo htmlspecialchars($stall['opening_hours']); ?></p>

    <!-- Display Existing Reviews -->
    <h2>Existing Reviews</h2>
    <?php if (count($reviews) > 0): ?>
        <ul>
            <?php foreach ($reviews as $review): ?>
                <li><?php echo htmlspecialchars($review['review']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>

    <!-- Review Submission Form -->
    <h2>Submit a Review</h2>
    <form action="submit_review.php" method="post">
        <input type="hidden" name="stall_id" value="<?php echo htmlspecialchars($stallId); ?>">
        <textarea name="review_text" placeholder="Write your review..." required></textarea><br>
        <button type="submit">Submit Review</button>
    </form>
</body>
</html>
