<?php
header('Content-Type: application/json');

$userID = $_GET['userID'] ?? '';

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

$sql = "SELECT Name, RSOID FROM rsos";

$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $sql = "SELECT * FROM memberships WHERE RSOID = ? AND UserID = ?;";
        $selectStmt = $conn->prepare($sql);
        $selectStmt->bind_param("ii", $row['RSOID'], $userID);
        $selectStmt->execute();
        $result1 = $selectStmt->get_result();

        if ($result1->num_rows > 0) {
            $row['Membership'] = 'Yes';
            $events[] = $row;
        }
        else{
            $row['Membership'] = 'No';
            $events[] = $row;
        }
    }
} else {
    echo "0 results";
}

echo json_encode($events);

$conn->close();
?>
