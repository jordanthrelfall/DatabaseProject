<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['UserID'];
    $email = $_POST['Email'];
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
    $stmt = $conn->prepare("INSERT INTO users (UserID, Email, Password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $uid, $email, $password);

    // Execute and check success
    if ($stmt->execute()) {
        echo $uid;
    } else {
        echo "Registration failed";
    }

    $stmt->close();
    $conn->close();
}
?>
