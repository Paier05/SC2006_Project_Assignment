
<?php
session_start();
include "../config.php";
/*
$serverName = "hawker.database.windows.net";
$connectionOptions = array(
    "Database" => "Hawker_App",
    "UID" => "team26",
    "PWD" => "Wearegood!",
    "LoginTimeout" => 30,
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);


// Check if the connection is successful
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
*/
// Get form data
$domain = $_POST['domain']; 
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing

$_SESSION['mail'] = $_POST['email'];

// SQL and parameters array
$sql = "";
$params = array();

if ($domain == "user") {
    $status = 'active';
    // Insert into database for user
    $sql = "INSERT INTO users (domain, email, password, status) VALUES ('$domain', '$email', '$password', '$status')";
    $params = array($domain, $email, $password, $status);
} else {
    // Check if file is uploaded and move it to the uploads directory
    if (isset($_FILES['license']) && $_FILES['license']['error'] == 0) {
        $licenseFile = $_FILES['license'];
        $licensePath = 'uploads/' . basename($licenseFile['name']);
        
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($licenseFile['tmp_name'], $licensePath)) {
            $status = 'pending';
            // Insert into database for hawker
            $sql = "INSERT INTO users (domain, email, password, license, status) VALUES ('$domain', '$email', '$password', '$licensePath', '$status')";
            $params = array($domain, $email, $password, $licensePath, $status);
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        echo "No file uploaded or upload error.";
        exit();
    }
}

// Execute the query using sqlsrv
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    header("Location: ../ConfirmationPage/confirmation.php"); // Redirect to the login page   // Can also redirect to php file for user specific webpage
    echo "Customer registration successful!";
}

sqlsrv_close($conn);
?>