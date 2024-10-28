<?php
session_start();

include "../config.php"; // Start the connection with the Azure SQL database

// Assuming the user_id is stored in the session after login
if (!isset($_SESSION['user_id'])) {
    die("User not authenticated.");
}

$user_id = $_SESSION['user_id'];

// Fetch POST data
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
}

// Fetch the stored hashed password for the user from the database
$sql = "SELECT password FROM users WHERE user_id = ?";
$params = array($user_id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    $hashed_password = $row['password'];

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Password is correct, proceed to delete the account
        $delete_sql = "DELETE FROM users WHERE user_id = ?";
        $delete_stmt = sqlsrv_query($conn, $delete_sql, $params);

        if ($delete_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "Account deleted successfully.";
    } else {
        echo "Incorrect password. Please try again.";
    }
} else {
    echo "User not found.";
}

// Free statement and close the connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
