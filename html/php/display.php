<?php
$servername = "localhost";
$username = "root"; // default WAMP server username
$password = ""; // default WAMP server password is empty
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name FROM testtable";
$result = $conn->query($sql);

$data = array(); // Initialize an empty array

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row; // Add each row to the array
    }
    echo json_encode($data); // Encode the array as JSON and output it
} else {
    echo json_encode(array('message' => '0 results')); // No results found
}
$conn->close();
?>
