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

// Fetch RSOIDs where the user is a member
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

// Fetch public and university-specific private events
$publicPrivateEventsQuery = "SELECT * FROM events WHERE Visibility='public' OR (Visibility = 'private' AND UniversityID = ?)";
$stmt = $conn->prepare($publicPrivateEventsQuery);
$stmt->bind_param("s", $UniversityID);
$stmt->execute();
$publicPrivateResult = $stmt->get_result();
$events = [];

while ($row = $publicPrivateResult->fetch_assoc()) {
    $events[$row['EventID']] = $row; // Use EventID as key to avoid duplicates
}
$stmt->close();

// Fetch events associated with RSOIDs
if (!empty($rsoIDs)) {
    $placeholders = implode(',', array_fill(0, count($rsoIDs), '?'));
    $types = str_repeat('i', count($rsoIDs));
    $rsoEventsQuery = "SELECT * FROM events WHERE RSOID IN ($placeholders)";
    $stmt = $conn->prepare($rsoEventsQuery);
    $stmt->bind_param($types, ...$rsoIDs);
    $stmt->execute();
    $rsoResult = $stmt->get_result();

    while ($row = $rsoResult->fetch_assoc()) {
        $events[$row['EventID']] = $row; // Use EventID as key to avoid duplicates
    }
    $stmt->close();
}

if (empty($events)) {
    $events = ["message" => "No events found."];
} else {
    $events = array_values($events); // Re-index the array to ensure JSON array format
}

echo json_encode($events);

$conn->close();
?>
