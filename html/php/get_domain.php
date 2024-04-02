<?php
// verifyDomain.php using MySQLi

// Database configuration
$host = 'localhost'; // or your database host
$dbname = 'project';
$username = 'root';
$password = '';

// Connect to the database
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Assuming the domain is passed via a GET request
$domain = isset($_GET['domain']) ? $_GET['domain'] : '';

// Prepare and execute the query
if ($stmt = $mysqli->prepare("SELECT UniversityID FROM universities WHERE Domain = ?")) {
    // Bind parameters (s - string)
    $stmt->bind_param("s", $domain);
    
    // Execute the statement
    $stmt->execute();
    
    // Bind the result variable
    $stmt->bind_result($universityID);
    
    if ($stmt->fetch()) {
        echo $universityID; // Return the UniversityID
    } else {
        echo "Domain not found"; // Or you could return a specific code or false to indicate not found
    }
    
    // Close statement
    $stmt->close();
} else {
    echo "Failed to prepare statement";
}

// Close connection
$mysqli->close();
?>
