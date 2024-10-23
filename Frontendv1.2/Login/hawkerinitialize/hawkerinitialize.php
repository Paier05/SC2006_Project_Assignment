<?php
// Start session
session_start();
include '../config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form inputs
    $stall_name = $_POST['stall_name'];
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];
    $hawker_center_id = $_POST['hawker_center_id'] + 1; // Retrieve selected hawker center ID

    // Handle the selected opening days (checkboxes)
    $selected_days = isset($_POST['opening_days']) ? $_POST['opening_days'] : array();
    
    // Create the 7-bit binary representation for opening days
    $binary_days = '';
    for ($i = 1; $i <= 7; $i++) {
        $binary_days .= in_array($i, $selected_days) ? '1' : '0';
    }

    // Array for menu item details
    $menu_items = $_POST['item_name'];
    $menu_descriptions = $_POST['item_description'];
    $menu_prices = $_POST['item_price'];
    $menu_images = $_FILES['item_image']; // Array for file uploads

    // Get the stall owner from session
    $user_id = $_SESSION['user_id']; // Assuming user_id is already stored in session during login

    // Default fault reports as empty
    $fault_reports = '';

    date_default_timezone_set('Asia/Singapore');
    $current_time = date('h:iA'); // Format: "09:00AM" or "11:30PM"
    $opening_status = ($current_time >= $opening_time && $current_time <= $closing_time) ? 1 : 0;

    // Combine opening_time and closing_time into opening_hours format
    $opening_hours = strtolower(date('h:iA', strtotime($opening_time))) . '-' . strtolower(date('h:iA', strtotime($closing_time)));

    // Start a transaction
    sqlsrv_begin_transaction($conn);

    try {
        // Insert into HawkerStalls and get the inserted stall_id
        $query = "INSERT INTO HawkerStalls (stall_name, stall_owner, opening_status, fault_reports, opening_days, opening_hours, hawker_center_id)
                  OUTPUT INSERTED.id
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = array($stall_name, $user_id, $opening_status, $fault_reports, $binary_days, $opening_hours, $hawker_center_id);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        // Fetch the last inserted stall_id
        sqlsrv_fetch($stmt);
        $hawker_stall_id = sqlsrv_get_field($stmt, 0); // Get the stall_id from the output

        // Directory for image uploads
        $target_directory = __DIR__ . "/uploads/";

        // Check if the uploads directory exists, if not, create it
        if (!is_dir($target_directory)) {
            mkdir($target_directory, 0777, true); // Create the uploads directory
        }

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
                $menu_query = "INSERT INTO StallMenus (StallID, ItemName, ItemDescription, ItemImage, price)
                               VALUES (?, ?, ?, ?, ?)";
                $menu_params = array($hawker_stall_id, $item_name, $item_description, $item_image, $price); // Use $hawker_stall_id
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
        header("Location: ../hawkermain/hawkermain.html");
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

// SQL query to select the NameOfCenter column from the HawkerCenter table
$query = "SELECT ID, NameOfCenter FROM HawkerCenter";

// Execute the query
$stmt = sqlsrv_query($conn, $query);

if ($stmt === false) {
    // Handle error if the query fails
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the results and store them in the array
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $hawker_centers_options[] = $row['NameOfCenter']; // Add each center name to the array
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initialize Hawker Profile</title>
    <link rel="stylesheet" href="HawkerInitializeStyles.css"> <!-- Linking external CSS -->
</head>
<body>

    <h1>Initialize Your Hawker Profile</h1>
    <div class="form-container">
        <form action="hawkerinitialize.php" method="POST" enctype="multipart/form-data"> 

            <label for="stall_name">Stall Name:</label>
            <input type="text" id="stall_name" name="stall_name" required>

            <label for="hawker_center_id">Choose Hawker Center:</label>
            <select id="hawker_center_id" name="hawker_center_id" required>
                <option value="">Select a center</option>
                <?php foreach ($hawker_centers_options as $key => $text): ?>
                    <option value="<?= htmlspecialchars($key) ?>">
                        <?= htmlspecialchars($text) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="opening-days-section">
                <label for="opening_days">Opening Days:</label><br>
                <label><input type="checkbox" name="opening_days[]" value="1"> Monday</label>
                <label><input type="checkbox" name="opening_days[]" value="2"> Tuesday</label>
                <label><input type="checkbox" name="opening_days[]" value="3"> Wednesday</label>
                <label><input type="checkbox" name="opening_days[]" value="4"> Thursday</label>
                <label><input type="checkbox" name="opening_days[]" value="5"> Friday</label>
                <label><input type="checkbox" name="opening_days[]" value="6"> Saturday</label>
                <label><input type="checkbox" name="opening_days[]" value="7"> Sunday</label><br><br>
            </div>

            <label for="opening_hours">Opening Hours:</label><br>
            From: <input type="time" id="opening_time" name="opening_time" required> 
            To: <input type="time" id="closing_time" name="closing_time" required><br><br>

            <label for="menu_items">Menu Items:</label>
            <div class="menu-items-container" id="menu-items-container">
                <div class="menu-item-row">
                    <input type="text" name="item_name[]" placeholder="Food Name" required>
                    <input type="text" name="item_price[]" class="price-input" placeholder="Price (SGD)" required>
                    <input type="file" name="item_image[]" accept="image/*" required>
                    <textarea name="item_description[]" placeholder="Description" rows="2"></textarea>
                </div>
            </div>
            <button type="button" onclick="addMenuItem()">Add Another Menu Item</button>

            <button type="submit">Initialize Profile</button>
        </form>
    </div>

    <script>
        function addMenuItem() {
            const container = document.getElementById('menu-items-container');
            const newItemRow = document.createElement('div');
            newItemRow.className = 'menu-item-row';
            newItemRow.innerHTML = `
                <input type="text" name="item_name[]" placeholder="Food Name" required>
                <input type="text" name="item_price[]" class="price-input" placeholder="Price (SGD)" required>
                <input type="file" name="item_image[]" accept="image/*" required>
                <textarea name="item_description[]" placeholder="Description" rows="2"></textarea>
            `;
            container.appendChild(newItemRow);
        }
    </script>

</body>
</html>