<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$eventId = $data['eventId'] ?? '';
$commentText = $data['text'] ?? '';
$commentRating = $data['rating'] ?? '';
$userID = $data['userID'] ?? '';

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
$stmt = $conn->prepare("INSERT INTO comments (UserID, EventID, Comment, Rating) VALUES (?, ?, ?, ?);");
$stmt->bind_param("iisi", $userID, $eventId, $commentText, $commentRating);

if($stmt->execute()) {
    // If insert is successful, you might want to return a success message or the ID of the inserted comment
    echo json_encode(['message' => 'Comment added successfully']);
} else {
    // If insert fails, return an error message
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to add comment']);
}

// Close resources
$stmt->close();
$conn->close();
?>
