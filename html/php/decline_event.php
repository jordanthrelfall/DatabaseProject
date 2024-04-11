<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you're passing the ID of the event to approve
    $json_str = file_get_contents('php://input');
    
    // Get as an object
    $json_obj = json_decode($json_str);

    $eventIdToApprove = $json_obj->eventId;

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

    $selectQuery = "DELETE FROM RequestEvent WHERE RequestID = ?";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param("i", $eventIdToApprove);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    $selectStmt->close();
    $conn->close();
}
?>
