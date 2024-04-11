<?php
header('Content-Type: application/json');

$eventId = $_GET['eventId'] ?? '';

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT CommentID,Comment,UserID,Rating FROM comments WHERE EventID = ?;");
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];

while($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

$stmt->close();

echo json_encode($comments);

// Close resources

$conn->close();
?>