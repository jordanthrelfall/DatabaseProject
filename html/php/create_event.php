<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect value of input fields
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $eventDescription = $_POST['eventDescription'];
    $eventCategory = $_POST['eventCategory'];
    $eventLocationName = $_POST['eventLocationName'];
    $eventLatitude = $_POST['eventLatitude'];
    $eventLongitude = $_POST['eventLongitude'];
    $eventContactPhone = $_POST['eventContactPhone'];
    $eventContactEmail = $_POST['eventContactEmail'];
    $eventVisibility = $_POST['eventVisibility'];

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

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO events (Name, Date, Time, Description, Category, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssddsss", $eventName, $eventDate, $eventTime, $eventDescription, $eventCategory, $eventLocationName, $eventLatitude, $eventLongitude, $eventContactPhone, $eventContactEmail, $eventVisibility);

    if ($stmt->execute()) {
        echo "New event created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
