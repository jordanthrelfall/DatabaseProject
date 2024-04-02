<?php
// create_university.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect input from the form
    $name = $_POST['Name'];
    $location = $_POST['Location'];
    $description = $_POST['Description'];
    $numberOfStudents = $_POST['NumberOfStudents'];
    $pictureURL = $_POST['PictureURL'];
    $domain = $_POST['Domain'];

    // Database configuration
    $servername = "localhost";
    $username = "root"; // Change as per your database user
    $password = ""; // Change as per your database password
    $dbname = "project"; // Your database name

    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO universities (Name, Location, Description, NumberOfStudents, PictureURL, Domain) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $name, $location, $description, $numberOfStudents, $pictureURL, $domain);

    // Execute the statement and check if it succeeds
    if ($stmt->execute()) {
        echo "New university profile created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Not a POST request
    echo "Invalid request method.";
}
?>
