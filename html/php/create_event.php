<?php
    $data = json_decode(file_get_contents('php://input'), true);
    // Collect value of input fields
    $eventName = $data['eventName'];
    $eventDate = $data['eventDate'];
    $eventTime = $data['eventTime'];
    $eventDescription = $data['eventDescription'];
    $eventCategory = $data['eventCategory'];
    $eventLocationName = $data['eventLocationName'];
    $eventLatitude = $data['eventLatitude'];
    $eventLongitude = $data['eventLongitude'];
    $eventContactPhone = $data['eventContactPhone'];
    $eventContactEmail = $data['eventContactEmail'];
    $eventVisibility = $data['eventVisibility'];
    $universityID = $data['universityID'];
    $userID = $data['userID'];

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
    $stmt = $conn->prepare("INSERT INTO events (Name, Date, Time, Description, Category, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility,UniversityID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssddsssi", $eventName, $eventDate, $eventTime, $eventDescription, $eventCategory, $eventLocationName, $eventLatitude, $eventLongitude, $eventContactPhone, $eventContactEmail, $eventVisibility, $universityID);

    if ($stmt->execute()) {
        echo "New event created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
?>
