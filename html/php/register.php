<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['UniversityID'];
    $email = $_POST['Email'];
    $password = $_POST['Password']; // Reminder: Hash the password in a real application

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
    $stmt = $conn->prepare("INSERT INTO users (UniversityID, Email, Password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $uid, $email, $password);

    // Execute and check success
    if ($stmt->execute()) {
        // Prepare a new statement to fetch the UserID
        $stmt->close(); // Close the previous statement before preparing a new one
        $stmt = $conn->prepare("SELECT UserID FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($userID); // Bind the result variable
        if ($stmt->fetch()) {
            echo $userID; // Echo the UserID
        } else {
            echo "User ID not found."; // Handle case where UserID could not be fetched
        }
    } else {
        echo "Registration failed";
    }

    $stmt->close();
    $conn->close();
}
?>
