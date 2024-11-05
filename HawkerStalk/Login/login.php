<?php
// To start a session and continue as this user
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; // Password withhout encryption
    $domain = $_POST['domain'];

    // Query to fetch the hashed password for the given email
    $query = "SELECT user_id, password, domain, status FROM users WHERE email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user) {
        // Check the status of the user
        if ($user['status'] === 'pending') {
            echo "Your account is pending approval. Please contact support.";
            exit; // Stop further execution
        } elseif ($user['status'] === 'suspended') {
            echo "Your account has been suspended!";
            exit; // Stop further execution
        }

        // Check if the domain matches
        if ($domain === $user['domain']) {
            // Domain matches, now verify the password
            // Use password_verify will automatically hash the original password and compare with the hashed password
            if (password_verify($password, $user['password'])) {
                // Password matches, start session and redirect
                $_SESSION['email'] = $email;
                echo "Login successful!";

                // Redirect to a protected page (e.g., dashboard.php)
                if ($domain === 'admin') {
                    header("Location: ./adminmain/user_management.php");
                } elseif ($domain === "hawker") {
                    // Store user_id in session
                    $_SESSION['user_id'] = $user['user_id']; 

                    // Now check if the hawker has initialized their profile
                    $user_id = $user['user_id'];
                    $checkQuery = "SELECT stall_owner FROM HawkerStalls WHERE stall_owner = ?";
                    $checkParams = array($user_id);
                    $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);

                    if ($checkStmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    // Check if any rows were returned
                    if (sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC)) {
                        // If the user_id is found, redirect to the hawker page
                        header("Location: ./hawkermain/hawkermain.html");
                    } else {
                        // If not found, redirect to the initialization page
                        header("Location: ./hawkerinitialize/hawkerinitialize.php");
                    }

                    sqlsrv_free_stmt($checkStmt);
                    
                } else {
                    header("Location: ./usermain/usermain.html");
                }
                exit;
            } else {
                // Invalid password
                echo "Invalid email or password.";
            }
        } else {
            // Invalid domain
            echo "Invalid domain.";
        }
    } else {
        // Invalid email, cannot find in database
        echo "Invalid email or password.";
    }

    sqlsrv_free_stmt($stmt);
}
?>
