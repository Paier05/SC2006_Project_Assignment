<?php
include '../config.php';
<<<<<<< HEAD

// Get the stall ID from the request
$stallId = isset($_GET['stall_id']) ? (int)$_GET['stall_id'] : 0;

if ($stallId!=0) {
    // Fetch stall details
    $sql = "SELECT * FROM HawkerStalls WHERE id = ?";
    $params = array($stallId);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $stall = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

} else {
    echo "Invalid stall ID.";
    exit;
}
?>?>
=======
header('Content-Type: application/json');

// Check if request is to fetch stall details (GET) or submit fault report (POST)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get stall ID from the request
    $id = isset($_GET['stall_id']) ? (int)$_GET['stall_id'] : 0;

    if ($id !== 0) {
        // Fetch stall details
        $sql = "SELECT stall_name, opening_hours FROM HawkerStalls WHERE id = ?";
        $params = array($id);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt && $stall = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo json_encode($stall);
        } else {
            echo json_encode(["error" => "Stall not found"]);
        }

        sqlsrv_free_stmt($stmt);
    } else {
        echo json_encode(["error" => "Invalid stall ID"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $stall_id = isset($data['stall_id']) ? (int)$data['stall_id'] : 0;
    $fault_report = isset($data['fault_text']) ? $data['fault_text'] : '';

    if ($stall_id !== 0 && !empty($fault_report)) {
        // Insert fault report
        $sql = "INSERT INTO faultReport (stall_owner, fault_report) VALUES (?, ?)";
        $params = array($stall_id, $fault_report);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            echo json_encode(["success" => true, "message" => "Fault Report submitted successfully!"]);
        } else {
            echo json_encode(["success" => false, "error" => sqlsrv_errors()]);
        }

        sqlsrv_free_stmt($stmt);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid data provided"]);
    }
}

sqlsrv_close($conn);
?>
>>>>>>> 084d730c25e271835e43c8f86596be246f7ac7cb
