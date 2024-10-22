<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hawker_stalk";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection works
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data and sanitize inputs
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$review = isset($_POST['review']) ? htmlspecialchars($_POST['review']) : '';
$crowd = isset($_POST['crowd']) ? intval($_POST['crowd']) : 0;
$time = isset($_POST['time']) ? htmlspecialchars($_POST['time']) : '';

// Insert the review into the database
$sql = "INSERT INTO reviews (stall_name, rating, review, crowd_level, time)
        VALUES ('Western Stall @ Bugis', '$rating', '$review', '$crowd', '$time')";

// Check if the insertion is successful
if ($conn->query($sql) === TRUE) {
    echo "Review successfully submitted!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
