<?php
header('Content-Type: application/json');

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// SQL to fetch event requests
$sql = "SELECT RequestID, Name, Category, Description, Time, Date, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility FROM RequestEvent;";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    echo json_encode($events);
} else {
    echo json_encode(['message' => "No event requests found"]);
}

$conn->close();
?>
