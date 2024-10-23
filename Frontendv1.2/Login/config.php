<?php
// php for connecting to database

// Connection settings

$serverName = "tcp:hawker.database.windows.net,1433";
$connectionOptions = array(
    "Database" => "Hawker_App",
    "Uid" => "team26",
    "PWD" => "Wearegood!",
    "Encrypt" => 1, 
    "TrustServerCertificate" => 0
);

// Establish the connection using sqlsrv_connect
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check if the connection was successful
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
