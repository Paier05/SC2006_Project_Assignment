<?php
// Database connection parameters for Azure SQL
$serverName = "tcp:hawker.database.windows.net,1433";
$database = "Hawker_App";
$username = "team26";
$password = "Wearegood!";

try {
    // Create a new PDO connection to the Azure SQL database
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to SQL Server: " . $e->getMessage());
}

// Get form data
$domain = $_POST['domain'];
$email = $_POST['email'];
$passwordHashed = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing

// Check the domain type (user or hawker)
if ($domain == "user") {
    // Prepare SQL query to insert into 'users' table for a regular user
    $sql = "INSERT INTO users (domain, email, password) VALUES (:domain, :email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':domain', $domain);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHashed);
} else {
    // Handle file upload for hawker
    if (isset($_FILES['license']) && $_FILES['license']['error'] == 0) {
        $licenseFile = $_FILES['license'];
        $licensePath = 'uploads/' . basename($licenseFile['name']);
        
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($licenseFile['tmp_name'], $licensePath)) {
            // Prepare SQL query to insert into 'users' table for hawker
            $sql = "INSERT INTO users (domain, email, password, license) VALUES (:domain, :email, :password, :license)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':domain', $domain);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $passwordHashed);
            $stmt->bindParam(':license', $licensePath);
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        echo "No file uploaded or upload error.";
        exit();
    }
}

// Execute the SQL query and check if successful
if ($stmt->execute()) {
    // Redirect to a specific page or display a success message
    header("Location: ../index.html");
    echo "Customer registration successful!";
} else {
    echo "Error: " . $stmt->errorInfo()[2];
}

// Close the connection
$conn = null;

