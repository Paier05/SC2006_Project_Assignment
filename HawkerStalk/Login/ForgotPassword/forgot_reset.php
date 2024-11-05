<?php
session_start();

include "../config.php"; // Start the connection with the Azure SQL database

// Get data from the session
$domain = $_SESSION['forgot_domain'];
$email = $_SESSION['forgot_email'];

// Get the new password from the front end and hash it
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password

// Prepare the SQL statement
$sql = "UPDATE users SET password = ? WHERE domain = ? AND email = ?";

// Prepare and execute the query using sqlsrv
$params = array($password, $domain, $email);
$stmt = sqlsrv_prepare($conn, $sql, $params);

if ($stmt) {
    // Execute the statement
    if (sqlsrv_execute($stmt)) {
        echo "Password updated successfully!";
    } else {
        // Handle error during execution
        echo "Error: Could not execute the update. ";
        print_r(sqlsrv_errors()); // Print SQL Server error details
    }

    header("Location: ./end_forgot_password.html");
} else {
    // Handle error during preparation
    echo "Error: Could not prepare the SQL statement. ";
    print_r(sqlsrv_errors());
}

// Close the connection
sqlsrv_close($conn);
?>
