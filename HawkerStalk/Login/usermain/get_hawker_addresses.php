<?php
// php for retrieving location data of hawker centers
include '../config.php';

// Query to retrieve the locations
$sql = "SELECT ID, NameOfCenter, LocationOfCenter, Latitude, Longitude FROM HawkerCenter WHERE Latitude IS NOT NULL AND Longitude IS NOT NULL";
$stmt = sqlsrv_query($conn, $sql);

$locations = [];

if($stmt !== false) {
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Store fetched locations into the array
        $locations[] = [
            'id' => $row['ID'],
            'name' => $row['NameOfCenter'],
            'location' => $row['LocationOfCenter'],
            'lat' => $row['Latitude'],
            'lng' => $row['Longitude'],
        ];
    }
}

// Close connection
sqlsrv_close($conn);

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($locations);
?>
