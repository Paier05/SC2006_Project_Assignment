<?php
// To start a session and continue as this user
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; // Password withhout encryption
    $domain = $_POST['domain'];

    // Query to fetch the hashed password for the given email
    $query = "SELECT password, domain FROM users WHERE email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user) {
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
                    header("Location: ./admintest/usermanagement.php");
                } elseif ($domain === "hawker") {
                    header("Location: ./hawkermain/hawkermain.html");
                } else {
                    header("Location: ./maptest/maptest.html");
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
