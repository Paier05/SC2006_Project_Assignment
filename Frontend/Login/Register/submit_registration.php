<?php
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

if ($domain == "user") {
    // Insert into database for user
    $sql = "INSERT INTO users (domain, email, password) VALUES ('$domain', '$email', '$password')";
} else {
    // Check if file is uploaded and move it to the uploads directory
    if (isset($_FILES['license']) && $_FILES['license']['error'] == 0) {
        $licenseFile = $_FILES['license'];
        $licensePath = 'uploads/' . basename($licenseFile['name']);
        
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($licenseFile['tmp_name'], $licensePath)) {
            // Insert into database for hawker
            $sql = "INSERT INTO users (domain, email, password, license) VALUES ('$domain', '$email', '$password', '$licensePath')";
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        echo "No file uploaded or upload error.";
        exit();
    }
}

if ($conn->query($sql) === TRUE) {
    header("Location: ../index.html"); // Redirect to the login page   // Can also redirect to php file for user specific webpage
    echo "Customer registration successful!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
