<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect value of input fields
    $name = $_POST['Name'];
    $admin = $_POST['Admin'];
    // m1, m2, m3, m4 variables are collected but not used in the code provided.

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

    // Corrected SELECT query
    $sql = $conn->prepare("SELECT UserID, UniversityID FROM users WHERE Email=?;");
    if (!$sql) {
        die("Prepare failed: " . $conn->error);
    }
    $sql->bind_param("s", $admin);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Prepare and bind INSERT statement
        $stmt = $conn->prepare("INSERT INTO rsos (Name, AdminID, UniversityID) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sss", $name, $row['UserID'], $row['UniversityID']);

        if ($stmt->execute()) {
            echo "New RSO created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Admin not found";
    }

    $conn->close();
}
?>
