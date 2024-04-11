<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$rsoID = $data['rsoID'] ?? '';
$userID = $data['userID'] ?? '';

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

$sql = "SELECT * FROM memberships WHERE RSOID = ? AND UserID = ?;";
$selectStmt = $conn->prepare($sql);
$selectStmt->bind_param("ii", $rsoID, $userID);
$selectStmt->execute();
$result = $selectStmt->get_result();

$events = [];

if ($result->num_rows > 0) {
    // output data of each row
    echo "YES";
} else {
    echo "0 results";
}


$conn->close();
?>
