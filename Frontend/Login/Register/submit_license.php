<?php
session_start(); // Start session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in or hawker has the email in session
if (!isset($_SESSION['email'])) {
    echo "Error: No user session found.";
    exit();
}

// Get the email from session
$email = $_SESSION['email'];

// Check if form is submitted via POST and file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['license'])) {
    $licenseFile = $_FILES['license'];
    // The file uploaded will be put in directory uploads
    $licensePath = 'uploads/' . basename($licenseFile['name']);

    if ($licenseFile['error'] === UPLOAD_ERR_OK) {
        // Check if file is successfully uploaded
        if (move_uploaded_file($licenseFile['tmp_name'], $licensePath)) {
            // You can update the user's record with the license file path in the database
            // For simplicity, assuming that email is passed to identify the user
            // You'll need to adjust this part based on how you're tracking the user

            $sql = "UPDATE users SET license='$licensePath' WHERE email='$email'";

            if ($conn->query($sql) === TRUE) {
                session_destroy();
                header("Location: ../index.html"); // Redirect to the login page   // Can also redirect to php file for user specific webpage
                echo "License successfully uploaded!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error uploading license.";
        }
    } else {
        echo "Error uploading file. Error code: " . $licenseFile['error'];
    }
}

$conn->close();
?>
