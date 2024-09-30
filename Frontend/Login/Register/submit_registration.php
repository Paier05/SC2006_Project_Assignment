<?php
// Improved error handling for database connection
try {
    $conn = new PDO("sqlsrv:server = tcp:hawker.database.windows.net,1433; Database = Hawker_App", "team26", "Wearegood!");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Detailed error output
    echo "Connection failed: " . $e->getMessage();
    die(); // Stop execution if there's a connection error
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
        
        // Ensure the uploads directory is writable and move the uploaded file
        if (move_uploaded_file($licenseFile['tmp_name'], $licensePath)) {
            // Prepare SQL query to insert into 'users' table for hawker
            $sql = "INSERT INTO dbo.users (domain, email, password, license) VALUES (:domain, :email, :password, :license)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':domain', $domain);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $passwordHashed);
            $stmt->bindParam(':license', $licensePath);
        } else {
            // Handle file upload error
            echo "Error uploading file.";
            exit();
        }
    } else {
        // Handle missing file or upload error
        echo "No file uploaded or upload error.";
        exit();
    }
}

// Execute the SQL query and check if successful
if ($stmt->execute()) {
    // Redirect to a specific page or display a success message
    header("Location: ../index.html");
    exit(); // Make sure script execution stops after redirection
} else {
    // Improved error handling to show detailed SQL error
    $errorInfo = $stmt->errorInfo();
    echo "Error executing query: SQLSTATE: " . $errorInfo[0] . ", Driver error code: " . $errorInfo[1] . ", Message: " . $errorInfo[2];
}

// Close the connection
$conn = null;
?>
