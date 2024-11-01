<?php
// Start session
//ob_start();
session_start();
include '../config.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

///////////////////////////////////////////////////////////////////////////////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

    // Get the menu item from the form
    $uploadedItemJson = $_POST['menu_item'] ?? ''; // Get the single JSON item
    $uploadedItem = json_decode($uploadedItemJson, true) ?? []; // Decode JSON

    error_log(print_r($uploadedItem, true)); // Log the array contents to your server's error log

    // Check if the item is empty
    if (empty($uploadedItem)) {
        $response['message'] = "No menu item was submitted.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Query to get the StallID for the logged-in user
    $stallQuery = "SELECT id FROM HawkerStalls WHERE stall_owner = ?";
    $stallParams = array($user_id);
    $stallStmt = sqlsrv_query($conn, $stallQuery, $stallParams);

    if ($stallStmt === false || !sqlsrv_fetch($stallStmt)) {
        $response['message'] = "Error retrieving stall: " . print_r(sqlsrv_errors(), true);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $stallId = sqlsrv_get_field($stallStmt, 0);
    sqlsrv_free_stmt($stallStmt);

    // Handle image upload for the item
    $itemImage = null;
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] !== '') {
        $itemImage = $_FILES['image']['name'];
        $tempFilePath = $_FILES['image']['tmp_name'];

        if ($itemImage) {
            $targetDir = 'C:/xampp/htdocs/Frontend/Login/hawkerinitialize/uploads/';
            $uniqueImageName = basename($itemImage); // Use the original name
            $targetFilePath = $targetDir . $uniqueImageName;

            if (!move_uploaded_file($tempFilePath, $targetFilePath)) {
                $response['message'] = "Error uploading image for item: " . $uploadedItem['item_name'];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        }
    }

    // Insert the new menu item
    $itemName = $uploadedItem['item_name'];
    $itemDescription = $uploadedItem['item_description'];
    $itemPrice = $uploadedItem['item_price'];

    // Prepare the insert statement
    $insertQuery = "INSERT INTO StallMenus (StallID, ItemName, ItemDescription, ItemImage, Price) VALUES (?, ?, ?, ?, ?)";
    $insertParams = array($stallId, $itemName, $itemDescription, $itemImage, $itemPrice);
    $insertStmt = sqlsrv_query($conn, $insertQuery, $insertParams);

    // Check if the insertion was successful
    if ($insertStmt === false) {
        $error = sqlsrv_errors();
        error_log(print_r($error, true)); // Log the error for debugging
        $response['message'] = "Error inserting menu item: " . print_r($error, true);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    sqlsrv_free_stmt($insertStmt);

    // If we reach here, everything was successful
    $response['status'] = 'success';
    $response['message'] = 'Menu item saved successfully!';
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
    /*$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

    // Get the array of menu items from the form
    $uploadedItems = json_decode($_POST['menu_items'], true) ?? []; // Decode JSON
    //$uploadedItems = $_POST['menu_items'] ?? [];

    error_log(print_r($uploadedItems, true)); // Log the array contents to your server's error log

    // Check if the array is empty
    if (empty($uploadedItems)) {
        $response['message'] = "No menu items were submitted.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Query to get the StallID for the logged-in user
    $stallQuery = "SELECT id FROM HawkerStalls WHERE stall_owner = ?";
    $stallParams = array($user_id);
    $stallStmt = sqlsrv_query($conn, $stallQuery, $stallParams);

    if ($stallStmt === false || !sqlsrv_fetch($stallStmt)) {
        $response['message'] = "Error retrieving stall: " . print_r(sqlsrv_errors(), true);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $stallId = sqlsrv_get_field($stallStmt, 0);
    sqlsrv_free_stmt($stallStmt);

    // Delete all existing menu items for the current StallID
    
    $deleteQuery = "SELECT ItemImage FROM StallMenus WHERE StallID = ?";
    $deleteParams = array($stallId);
    $deleteStmt = sqlsrv_query($conn, $deleteQuery, $deleteParams);

    if ($deleteStmt === false) {
        $response['message'] = "Error retrieving existing menu items for deletion: " . print_r(sqlsrv_errors(), true);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $imagesToDelete = [];
    while ($row = sqlsrv_fetch_array($deleteStmt, SQLSRV_FETCH_ASSOC)) {
        $imagesToDelete[] = $row['ItemImage'];
    }
    sqlsrv_free_stmt($deleteStmt);

    // Remove old images
    foreach ($imagesToDelete as $imageName) {
        $imagePath = '../hawkerinitialize/uploads/' . $imageName;
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the file
        }
    }

    // Now delete the existing menu items
    $deleteQuery = "DELETE FROM StallMenus WHERE StallID = ?";
    $deleteParams = array($stallId);
    $deleteStmt = sqlsrv_query($conn, $deleteQuery, $deleteParams);

    if ($deleteStmt === false) {
        $response['message'] = "Error deleting existing menu items: " . print_r(sqlsrv_errors(), true);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    sqlsrv_free_stmt($deleteStmt);
    */

    // Check if the array is empty
    if (empty($uploadedItems)) {
        $response['message'] = "No menu items were submitted.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    // Insert new menu items
    foreach ($uploadedItems as $index => $item) {
        $itemName = $item['item_name'];
        $itemDescription = $item['item_description'];
        $itemPrice = $item['item_price'];

        // Handle image upload for each item
        if (isset($_FILES['image']['name'][$index])) {
            $itemImage = $_FILES['image']['name'][$index];
            $tempFilePath = $_FILES['image']['tmp_name'][$index];

            if ($itemImage) {
                $targetDir = 'C:/xampp/htdocs/Frontend/Login/hawkerinitialize/uploads/';
                //$uniqueImageName = uniqid() . '_' . basename($itemImage);
                $uniqueImageName = basename($itemImage);
                $targetFilePath = $targetDir . $uniqueImageName;

                if (move_uploaded_file($tempFilePath, $targetFilePath)) {
                    // Prepare the insert statement
                    $insertQuery = "INSERT INTO StallMenus (StallID, ItemName, ItemDescription, ItemImage, Price) VALUES (?, ?, ?, ?, ?)";
                    $insertParams = array($stallId, $itemName, $itemDescription, $uniqueImageName, $itemPrice);
                    $insertStmt = sqlsrv_query($conn, $insertQuery, $insertParams);

                    // Check if the insertion was successful
                    if ($insertStmt === false) {
                        $error = sqlsrv_errors();
                        error_log(print_r($error, true)); // Log the error for debugging
                        $response['message'] = "Error inserting menu item: " . print_r($error, true);
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit();
                    }
                    sqlsrv_free_stmt($insertStmt);
                } else {
                    $response['message'] = "Error uploading image for item: " . $itemName;
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
            }
        }
    }

    // If we reach here, everything was successful
    $response['status'] = 'success';
    $response['message'] = 'Menu items saved successfully!';
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}*/

///////////////////////////////////////////////////////////////////////////////////////////////////

// Query to get the StallID for the logged-in user
$stallQuery = "SELECT id FROM HawkerStalls WHERE stall_owner = ?";
$stallParams = array($user_id);
$stallStmt = sqlsrv_query($conn, $stallQuery, $stallParams);

if ($stallStmt === false || !sqlsrv_fetch($stallStmt)) {
    die("Error retrieving stall: " . print_r(sqlsrv_errors(), true));
}

$stallId = sqlsrv_get_field($stallStmt, 0);
sqlsrv_free_stmt($stallStmt);

// Query to get all menu items for this stall
$menuQuery = "SELECT MenuItemID, ItemName, ItemDescription, ItemImage, Price FROM StallMenus WHERE StallID = ?";
$menuParams = array($stallId);
$menuStmt = sqlsrv_query($conn, $menuQuery, $menuParams);

if ($menuStmt === false) {
    die("Error retrieving menu items: " . print_r(sqlsrv_errors(), true));
}

$menuItems = array();
while ($row = sqlsrv_fetch_array($menuStmt, SQLSRV_FETCH_ASSOC)) {
    $menuItems[] = $row;
}

sqlsrv_free_stmt($menuStmt);
sqlsrv_close($conn);

// Set default item for display if menuItems array is populated
$defaultItem = count($menuItems) > 0 ? $menuItems[0] : null;

// Check if a default item exists
$imageName = $defaultItem ? htmlspecialchars($defaultItem['ItemImage']) : '';

// Build potential image path for absolute URL
$imagePath = !empty($imageName) ? 'http://localhost/Frontend/Login/hawkerinitialize/uploads/' . $imageName : '';
//ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Western Stall Menu</title>
    <link rel="stylesheet" href="addmenupage.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar with Menu Items -->
        <div class="sidebar">
            <?php foreach ($menuItems as $index => $item): ?>
                <div class="menu-item <?php echo $index === 0 ? 'selected' : ''; ?>"
                     data-id="<?php echo htmlspecialchars($item['MenuItemID']); ?>" 
                     data-name="<?php echo htmlspecialchars($item['ItemName']); ?>"
                     data-description="<?php echo htmlspecialchars($item['ItemDescription']); ?>"
                     data-price="<?php echo htmlspecialchars($item['Price']); ?>"
                     data-image="http://localhost/Frontend/Login/hawkerinitialize/uploads/<?php echo htmlspecialchars($item['ItemImage']); ?>">
                    <?php echo htmlspecialchars($item['ItemName']); ?>
                    <button class="delete-button" data-id="<?php echo htmlspecialchars($item['MenuItemID']); ?>" style="display:none;">Delete</button>
                    <span class="cross" data-id="<?php echo htmlspecialchars($item['MenuItemID']); ?>">✖</span>
                </div>
            <?php endforeach; ?>
            <div class="menu-item add-item" id="addItem">+</div>
        </div>

        <!-- Content Display for Selected Menu Item -->
        <div class="content">
            <div class="header">
                <h1>Western Stall</h1>
                <a href="../hawkermain.html"><button class="close-button">X</button></a>
            </div>

            <div class="details">
                <div class="image-upload-wrapper">
                    <img src="<?php echo $imagePath; ?>" alt="Food Image" class="food-image" id="foodImage">
                    <div class="upload-icon-wrapper" id="uploadIconWrapper" style="display: none;">
                        <img src="paperclip.png" alt="Upload Image" class="upload-icon">
                    </div>
                </div>
                <div class="info">
                    <label for="name">Name</label>
                    <input type="text" id="name" value="<?php echo $defaultItem ? htmlspecialchars($defaultItem['ItemName']) : ''; ?>"
                           placeholder="Enter Name" disabled>

                    <label for="description">Description</label>
                    <textarea id="description" placeholder="Enter Description" disabled><?php echo $defaultItem ? htmlspecialchars($defaultItem['ItemDescription']) : ''; ?></textarea>

                    <div class="price-section">
                        <label for="price">Price</label>
                        <div class="price-input">
                            <span class="currency-symbol">$</span>
                            <input type="number" id="price" value="<?php echo $defaultItem ? htmlspecialchars($defaultItem['Price']) : ''; ?>"
                                   placeholder="Enter Price" disabled>
                        </div>
                    </div>
                </div>
                <button class="edit-button" id="editButton">Edit</button>
            </div>
        </div>
    </div>

    <!-- Hidden file input for uploading images -->
    <input type="file" id="imageUpload" accept="image/*" style="display: none;">
    <script src="addmenupage.js"></script>
</body>
</html>
