<?php
// php for connecting to database

// Connection settings
$serverName = "hawker.database.windows.net";
$connectionOptions = array(
    "Database" => "Hawker_App",
    "Uid" => "team26",
    "PWD" => "Wearegood!"
);

// Establish the connection using sqlsrv_connect
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check if the connection was successful
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
