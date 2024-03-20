<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['uid'];
    $firstname = $_POST['FirstName'];
    $lastname = $_POST['LastName'];
    $password = $_POST['Password']; // In a real app, this should be hashed

    // Database connection parameters
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

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO users (uid, FirstName, LastName, Password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $uid, $firstname, $lastname, $password);

    // Execute and check success
    if ($stmt->execute()) {
        echo "Registration successful";
    } else {
        echo "Registration failed";
    }

    $stmt->close();
    $conn->close();
}
?>
