<?php
session_start();
include '../config.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die("User not logged in.");
}

// Fetch the existing opening hours
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT opening_hours, opening_days FROM HawkerStalls WHERE id = 4";  //error retrieving id from $user_id = $_SESSION['user_id']
    $params = array($user_id);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data = array(
            'opening_hours' => $row['opening_hours'],
            'opening_days' => $row['opening_days']
        );
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'No data found.'));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opening_days = $_POST['opening_days'];
    $opening_hours = $_POST['opening_hours'];

    // Update the opening hours and days
    $query = "UPDATE HawkerStalls SET opening_hours = ?, opening_days = ? WHERE id = 4"; //error retrieving id from $user_id = $_SESSION['user_id']
    $params = array($opening_hours, $opening_days, $user_id);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    echo 'Opening hours updated successfully.';
}
?>
