<?php
header('Content-Type: application/json');

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Name, Date, Time, Description, Category, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility FROM events";

$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
} else {
    echo "0 results";
}

echo json_encode($events);

$conn->close();
?>
