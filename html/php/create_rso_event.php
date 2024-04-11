<?php
// Decode JSON from request body
$data = json_decode(file_get_contents('php://input'), true);

// Collect value of input fields
$eventName = $data['eventName'];
$eventDate = $data['eventDate'];
$eventTime = $data['eventTime'];
$eventDescription = $data['eventDescription'];
$eventCategory = $data['eventCategory'];
$eventLocationName = $data['eventLocationName'];
$eventLatitude = $data['eventLatitude'];
$eventLongitude = $data['eventLongitude'];
$eventContactPhone = $data['eventContactPhone'];
$eventContactEmail = $data['eventContactEmail'];
$eventVisibility = $data['eventVisibility'];
$universityID = $data['universityID'];
$userID = $data['userID'];

// Database connection variables
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

// Retrieve the RSOID where the user is an admin
$getMemb = $conn->prepare("SELECT RSOID FROM memberships WHERE UserID = ? AND UserRole = 'admin'");
$getMemb->bind_param("i", $userID);
$getMemb->execute();
$membResult = $getMemb->get_result();
$rsoID = null; // Initialize RSOID

if ($membRow = $membResult->fetch_assoc()) {
    $rsoID = $membRow['RSOID']; // Use the RSOID if available
}
$getMemb->close();

// Prepare and bind statement for inserting the event
// Make sure your events table has a column for RSOID if you're going to store that information
$stmt = $conn->prepare("INSERT INTO events (Name, Date, Time, Description, Category, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility, UniversityID, RSOID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssddsssii", $eventName, $eventDate, $eventTime, $eventDescription, $eventCategory, $eventLocationName, $eventLatitude, $eventLongitude, $eventContactPhone, $eventContactEmail, $eventVisibility, $universityID, $rsoID);

// Execute the insert operation
if ($stmt->execute()) {
    echo "New event created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
