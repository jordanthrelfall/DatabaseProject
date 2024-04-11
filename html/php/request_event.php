<?php
header('Content-Type: application/json');
// The rest of your PHP script...

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you're receiving JSON, decode it here. Adjust variable names as needed.
    $data = json_decode(file_get_contents('php://input'), true);


    // Extract event details from $data
    $name = $data['eventName'];
    $category = $data['eventCategory'];
    $description = $data['eventDescription'];
    $time = $data['eventTime'];
    $date = $data['eventDate'];
    $locationName = $data['eventLocationName'];
    $latitude = $data['eventLatitude'];
    $longitude = $data['eventLongitude'];
    $contactPhone = $data['eventContactPhone'];
    $contactEmail = $data['eventContactEmail'];
    $visibility = $data['eventVisibility'];
    $universityID = $data['UniversityID']; // Make sure this is passed correctly from the front end
    $rsoID = isset($data['RSOID']) ? $data['RSOID'] : NULL; // Optional, depending on your logic

    // Database connection parameters
    $servername = "localhost";
    $dbusername = "root"; // Better to use a non-root user in production
    $dbpassword = "";
    $dbname = "project";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO RequestEvent (Name, Category, Description, Time, Date, LocationName, Latitude, Longitude, ContactPhone, ContactEmail, Visibility, UniversityID, RSOID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1)");
    
    // The 's' string here corresponds to the types of the variables being bound
    // "ssssssddsssi" corresponds to string, string, string, string (for datetime and date), string, string, double, double, string, string, string, integer, integer
    // Adjust the bind_param line as necessary to match the types of the columns in your database
    $stmt->bind_param("ssssssddsss", $name, $category, $description, $time, $date, $locationName, $latitude, $longitude, $contactPhone, $contactEmail, $visibility);

    // Execute and check success
    if ($stmt->execute()) {
        echo "Event request submitted successfully";
    } else {
        echo "Error submitting event request: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If not a POST request, you can handle it as needed
    echo "Invalid request method";
}
?>
