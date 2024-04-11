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
    
    // Fetch event details from RequestEvent table
    $selectQuery = "SELECT * FROM RequestEvent WHERE RequestID = ?";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param("i", $eventIdToApprove);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    if ($result->num_rows > 0) {
        $eventData = $result->fetch_assoc();

        // Now insert the fetched data into the main events table
        $insertQuery = "INSERT INTO events (Name, Date, Time, Description, Category, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ssssssddsss", $eventData['Name'], $eventData['Date'], $eventData['Time'], $eventData['Description'], $eventData['Category'], $eventData['LocationName'], $eventData['Latitude'], $eventData['Longitude'], $eventData['ContactPhone'], $eventData['ContactEmail'], $eventData['Visibility']);

        if ($insertStmt->execute()) {
            echo "Event approved and added successfully";
            // Optionally, delete the event from RequestEvent or update its status here
        } else {
            echo "Error: " . $insertStmt->error;
        }

        $selectQuery = "DELETE FROM RequestEvent WHERE RequestID = ?";
        $selectStmt = $conn->prepare($selectQuery);
        $selectStmt->bind_param("i", $eventIdToApprove);
        $selectStmt->execute();
        $result = $selectStmt->get_result();

        $insertStmt->close();
    } else {
        echo "No event found with the provided ID to approve.";
    }

    $selectStmt->close();
    $conn->close();
}
?>
