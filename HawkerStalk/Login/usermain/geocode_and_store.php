<?php
// This code only run for once
// list of hawker center: https://data.gov.sg/datasets?query=hawker&page=1&resultId=d_68a42f09f350881996d83f9cd73ab02f
// Location are stored as address
// Convert the address to geographicalcoordinate (longitude and latitude)

include '../config.php';

// Google Maps Geocoding API key
$apiKey = 'AIzaSyCzh4khfKnyc3v9JIN4LhAR0ZxCw8Xsa_s';

// Query to fetch the addresses that don't have latitude and longitude yet
$sql = "SELECT Id, NameOfCenter, LocationOfCenter FROM HawkerCenter WHERE Latitude IS NULL OR Longitude IS NULL";
$stmt = sqlsrv_query($conn, $sql);

if($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $id = $row['Id'];
    $address = $row['LocationOfCenter'];

    // Geocode the address
    $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;
    
    $response = file_get_contents($geocodeUrl);
    $responseData = json_decode($response, true);

    // Check if the response contains valid results
    if($responseData['status'] === 'OK') {
        $latitude = $responseData['results'][0]['geometry']['location']['lat'];
        $longitude = $responseData['results'][0]['geometry']['location']['lng'];

        // Update the database with the geocoded coordinates
        $updateSql = "UPDATE HawkerCenter SET Latitude = ?, Longitude = ? WHERE Id = ?";
        $params = array($latitude, $longitude, $id);
        $updateStmt = sqlsrv_query($conn, $updateSql, $params);

        if($updateStmt === false) {
            echo "Error updating coordinates for HawkerCenter ID $id: ";
            print_r(sqlsrv_errors(), true);
        } else {
            echo "Updated HawkerCenter ID $id with Latitude: $latitude, Longitude: $longitude \n";
        }
    } else {
        echo "Failed to geocode address for HawkerCenter ID $id: " . $responseData['status'] . "\n";
    }
}

// Close connection
sqlsrv_close($conn);
?>
