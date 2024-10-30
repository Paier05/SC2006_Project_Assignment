<?php
header("Content-Type: application/json");
session_start();

include '../config.php';

// Initialize response array
$response = ["success" => false];

try {
    // Get the JSON input data
    $data = json_decode(file_get_contents("php://input"), true);
    $stallId = $data['stall_id'];
    $reviewText = $data['review_text'];
    $rating = intval($data['rating']); // Convert rating to an integer
    $email = $_SESSION["email"];

    // Insert review and rating into the database
    $sql = "INSERT INTO StallRatings (hawker_stall_id, rating, review, user_email) VALUES (?, ?, ?, ?)";
    $params = array($stallId, $rating, $reviewText, $email);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        // Fetch existing sum and count from HawkerStalls
        $sql = "SELECT sum_rating, total_number_of_rating FROM HawkerStalls WHERE id = ?";
        $params = array($stallId);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt && $edit_review = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Update ratings
            $edit_review['sum_rating'] += $rating;
            $edit_review['total_number_of_rating'] += 1;

            // Update the database with the new values
            $sql = "UPDATE HawkerStalls SET sum_rating=?, total_number_of_rating=? WHERE id = ?";
            $params = array($edit_review['sum_rating'], $edit_review['total_number_of_rating'], $stallId);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt) {
                $response["success"] = true;
            } else {
                $response["error"] = "Failed to update HawkerStalls rating data.";
            }
        } else {
            $response["error"] = "Failed to fetch current ratings.";
        }
    } else {
        $response["error"] = "Failed to insert review.";
    }
} catch (Exception $e) {
    $response["error"] = "An unexpected error occurred: " . $e->getMessage();
}

// Return a single JSON response
echo json_encode($response);
?>
