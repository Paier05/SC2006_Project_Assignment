<?php
session_start();

include "../../config.php"; // Start the connection with the Azure SQL database

//----------------------------------------------------------------------------------------------------------------------------------------
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
//----------------------------------------------------------------------------------------------------------------------------------------

// Fetch the stored hashed password for the user from the database
$sql = "SELECT password FROM users WHERE user_id = ?";
$params = array($user_id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt); // Free the statement resource
//----------------------------------------------------------------------------------------------------------------------------------------

// First, query to get the hawker_stall_id based on the stall_owner
$hawker_stall_query = "SELECT id FROM HawkerStalls WHERE stall_owner = ?";
$hawker_params = array($user_id);
$hawker_stall_stmt = sqlsrv_query($conn, $hawker_stall_query, $hawker_params);

if ($hawker_stall_stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$hawker_stall_id = null;
if ($row_fetch_hawkerid = sqlsrv_fetch_array($hawker_stall_stmt, SQLSRV_FETCH_ASSOC)) {
    $hawker_stall_id = $row_fetch_hawkerid['id'];
} else {
    die("No hawker stall found for the logged-in user.");
}

sqlsrv_free_stmt($hawker_stall_stmt); // Free the statement resource

//----------------------------------------------------------------------------------------------------------------------------------------
if ($row) {
    $hashed_password = $row['password'];

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Password is correct, proceed to delete the account

        // Delete from users
        $delete_sql_user = "DELETE FROM users WHERE user_id = ?";
        $delete_stmt_user = sqlsrv_query($conn, $delete_sql_user, array($user_id));

        if ($delete_stmt_user === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($delete_stmt_user);

        // Delete from HawkerStalls
        $delete_sql_hawker = "DELETE FROM HawkerStalls WHERE stall_owner = ?";
        $delete_stmt_hawker = sqlsrv_query($conn, $delete_sql_hawker, array($user_id));

        if ($delete_stmt_hawker === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($delete_stmt_hawker);

        // Delete the stall rating
        $delete_sql_rating = "DELETE FROM StallRatings WHERE hawker_stall_id = ?";
        $delete_stmt_rating = sqlsrv_query($conn, $delete_sql_rating, array($hawker_stall_id));

        if ($delete_stmt_rating === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($delete_stmt_rating);

        // Delete the stall menu
        $delete_sql_menu = "DELETE FROM StallMenus WHERE StallID = ?";
        $delete_stmt_menu = sqlsrv_query($conn, $delete_sql_menu, array($hawker_stall_id));

        if ($delete_stmt_menu === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        sqlsrv_free_stmt($delete_stmt_menu);

        echo "Account deleted successfully.";
    } else {
        echo "Incorrect password. Please try again.";
    }
} else {
    echo "User not found.";
}

// Close the connection
sqlsrv_close($conn);
?>
