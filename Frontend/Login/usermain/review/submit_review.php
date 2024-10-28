<?php
header("Content-Type: application/json");
session_start();

include '../config.php';

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

$edit_review = [];

// Return success or error response
if ($stmt) {
    $sql = "SELECT sum_rating, total_number_of_rating FROM HawkerStalls WHERE id = ?";
    $params = array($stallId);
    $stmt = sqlsrv_query($conn, $sql, $params);
    /*
    if ($stmt !== false) {
        $edit_review = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $edit_review[0] += $rating;
        $edit_review[1] += 1;

        echo json_encode(["sum" => $edit_review]);
        
        $sql = "UPDATE HawkerStalls SET sum_rating=?, total_number_of_rating=? WHERE id = ?";
        $params = array($edit_review[0], $edit_review[1], $stalld);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            echo json_encode(["success" => true]);
        }
    }
    */
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>