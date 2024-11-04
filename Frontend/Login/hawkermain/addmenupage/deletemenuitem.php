<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"), true);
$menuItemId = $data['MenuItemID'] ?? null;

if ($menuItemId) {
    // Prepare SQL delete statement
    $deleteQuery = "DELETE FROM StallMenus WHERE MenuItemID = ?";
    $deleteParams = array($menuItemId);
    $deleteStmt = sqlsrv_query($conn, $deleteQuery, $deleteParams);

    if ($deleteStmt === false) {
        echo json_encode(["success" => false, "error" => sqlsrv_errors()]);
    } else {
        echo json_encode(["success" => true]);
    }

    sqlsrv_free_stmt($deleteStmt);
} else {
    echo json_encode(["success" => false, "error" => "Invalid item ID."]);
}

sqlsrv_close($conn);
?>