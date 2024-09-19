<?php
// Database connection settings
$servername = "localhost"; // For XAMPP, MAMP, WAMP, this is localhost
$username = "root";        // Default username for XAMPP/MAMP is "root"
$password = "";            // Default password for XAMPP/MAMP is an empty string
$dbname = "user_registration"; // The name of the database you created

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Registration successful - redirect to another page
        //echo "Registration successful!"; //For debugging
        header("Location: ../Login/index.html"); // Redirect to the login page   // Can also redirect to php file for user specific webpage
        exit(); // Stop the script after redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
