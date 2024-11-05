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
$imagePath = !empty($imageName) ? '../../hawkerinitialize/uploads/' . $imageName : '';
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
                     data-image="../../hawkerinitialize/uploads/<?php echo htmlspecialchars($item['ItemImage']); ?>">
                     <?php echo htmlspecialchars($item['ItemName']); ?>
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
                <button class="delete-button" id="deleteButton" style="display: none; color: red;">Delete</button>
            </div>
        </div>
    </div>

    <!-- Hidden file input for uploading images -->
    <input type="file" id="imageUpload" accept="image/*" style="display: none;">
    <script src="addmenupage.js"></script>
</body>
</html>
