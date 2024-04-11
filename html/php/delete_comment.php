<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$commentID = $data['commentID'] ?? '';

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
$stmt = $conn->prepare("DELETE FROM comments WHERE CommentID=?;");
$stmt->bind_param("i", $commentID);

if($stmt->execute()) {
    // If insert is successful, you might want to return a success message or the ID of the inserted comment
    echo json_encode(['message' => 'Comment deleted successfully']);
} else {
    // If insert fails, return an error message
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to delete comment']);
}

// Close resources
$stmt->close();
$conn->close();
?>
