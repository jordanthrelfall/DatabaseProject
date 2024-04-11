<?php
header('Content-Type: application/json');

// Authentication and validation code here

$data = json_decode(file_get_contents('php://input'), true);

$commentId = $data['commentId'];
$newText = $data['text'];
$newRating = $data['rating'];

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

$stmt = $conn->prepare("UPDATE comments SET Comment = ?, Rating = ? WHERE CommentID = ?");
$stmt->bind_param("sii", $newText, $newRating, $commentId);

if($stmt->execute()) {
    echo json_encode(['message' => 'Comment updated successfully']);
} else {
    echo json_encode(['error' => 'Failed to update comment']);
}

$stmt->close();
$conn->close();
?>
