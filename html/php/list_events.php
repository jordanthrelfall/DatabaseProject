<?php
header('Content-Type: application/json');

$UniversityID = isset($_GET['UniversityID']) ? $_GET['UniversityID'] : null;
$UserID = isset($_GET['UserID']) ? $_GET['UserID'] : null;

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

$rsoQuery = "SELECT RSOID FROM memberships WHERE UserID = ?";
$stmt = $conn->prepare($rsoQuery);
$stmt->bind_param("i", $UserID);
$stmt->execute();
$rsoResult = $stmt->get_result();
$rsoIDs = [];
while ($rsoRow = $rsoResult->fetch_assoc()) {
    $rsoIDs[] = $rsoRow['RSOID'];
}
$stmt->close();

$rsoIDsString = implode(',', array_map('intval', $rsoIDs));


$sql = "SELECT Name, Date, Time, Description, Category, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility FROM events WHERE Visibility='public' OR (Visibility = 'private' AND UniversityID = ?);";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $UniversityID);
$stmt->execute();
$result = $stmt->get_result();

$events = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
} else {
    $events = ["message" => "No events found."];
}

echo json_encode($events);

$stmt->close();
$conn->close();
?>
