<?php
    // Collect value of input field
    $rsoId = $_GET['rsoId'] ?? '';
    $userId = $_GET['UserID'] ?? '';

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

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO memberships (UserID, RSOID) VALUES (?, ?);");
    $stmt->bind_param("ss", $userId, $rsoId);

    $stmt->execute();
    $result = $stmt->get_result();

    echo "Success";

    $conn->close();
?>
