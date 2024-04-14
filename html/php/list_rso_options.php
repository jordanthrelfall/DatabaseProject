<?php
header('Content-Type: application/json');

// User ID from GET request or default to an empty string
$userID = $_GET['userID'] ?? '';

// Database configuration
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to fetch all RSOs where the user is an admin
$sql = "SELECT Name, rsos.RSOID FROM rsos
        JOIN memberships ON rsos.RSOID = memberships.RSOID
        WHERE memberships.UserID = ? AND memberships.UserRole = 'admin';";

// Prepare SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters and execute
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$rsos = []; // Array to hold the RSOs

if ($result->num_rows > 0) {
    // Fetch each RSO
    while($row = $result->fetch_assoc()) {
        $rsos[] = $row; // Add the RSO to the array
    }
} else {
    echo json_encode(["status" => "error", "message" => "No admin RSOs found for the user"]);
    exit;
}

echo json_encode($rsos); // Output the list of RSOs

$stmt->close(); // Close the statement
$conn->close(); // Close the connection
?>
