<?php
// Start session
session_start();
include 'config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form inputs
    $stall_name = $_POST['stall_name'];
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];
    
    // Array for menu item details
    $menu_items = $_POST['item_name']; 
    $menu_descriptions = $_POST['item_description'];
    $menu_prices = $_POST['item_price'];
    $menu_images = $_FILES['item_image']; // Array for file uploads

    // Get the stall owner from session
    $user_id = $_SESSION['user_id']; // Assuming user_id is already stored in session during login

    // Default fault reports as empty
    $fault_reports = '';

    // Calculate opening status based on current time, opening_time, and closing_time
    $current_time = date('h:iA'); // Format: "09:00AM" or "11:30PM"
    $opening_status = ($current_time >= $opening_time && $current_time <= $closing_time) ? 1 : 0;

    // Start a transaction
    sqlsrv_begin_transaction($conn);

    try {
        // Insert into HawkerStalls
        $query = "INSERT INTO HawkerStalls (stall_name, stall_owner, opening_time, closing_time, opening_status, fault_reports)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $params = array($stall_name, $user_id, $opening_time, $closing_time, $opening_status, $fault_reports);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        // Get the last inserted stall_id (if auto-incrementing)
        $hawker_stall_id = sqlsrv_insert_id($conn); // Adjust this for your Azure SQL setup

        // Directory for image uploads
        $target_directory = "uploads/";

        // Loop through each menu item
        foreach ($menu_items as $index => $item_name) {
            $item_description = $menu_descriptions[$index];
            $price = number_format($menu_prices[$index], 2); // Ensure price is 2 decimal points

            // Handle the file upload for the image
            $item_image = basename($menu_images['name'][$index]);
            $target_file = $target_directory . $item_image;

            // Check if the image upload was successful
            if (move_uploaded_file($menu_images['tmp_name'][$index], $target_file)) {
                // Successfully uploaded image, now insert into StallMenus
                $menu_item_id = $index + 1;

                $menu_query = "INSERT INTO StallMenus (HawkerID, MenuItemID, ItemName, ItemDescription, ItemImage, price)
                               VALUES (?, ?, ?, ?, ?, ?)";
                $menu_params = array($user_id, $menu_item_id, $item_name, $item_description, $item_image, $price);
                $menu_stmt = sqlsrv_query($conn, $menu_query, $menu_params);

                if ($menu_stmt === false) {
                    throw new Exception(print_r(sqlsrv_errors(), true));
                }
            } else {
                throw new Exception("Failed to upload image for menu item: " . $item_name);
            }
        }

        // Commit the transaction
        sqlsrv_commit($conn);

        // Redirect to the hawker's main page after initialization
        header("Location: ./hawkermain/hawkermain.html");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        sqlsrv_rollback($conn);
        echo "Error initializing hawker profile: " . $e->getMessage();
    }

    // Free the statement and connection resources
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>