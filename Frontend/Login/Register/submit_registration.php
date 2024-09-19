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

// Get form data
$domain = $_POST['domain']; 
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing

// Insert into database
$sql = "INSERT INTO users (domain, email, password) VALUES ('$domain', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    if ($domain === 'Hawker') {
        // Store email in session and redirect hawker to license upload page
        $_SESSION['email'] = $email;
        header("Location: ./license.html"); // Redirect to license upload page
        // echo "Hawker registration successful. Please submit your license.";
    } else {
        header("Location: ../index.html"); // Redirect to the login page   // Can also redirect to php file for user specific webpage
        echo "Customer registration successful!";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
