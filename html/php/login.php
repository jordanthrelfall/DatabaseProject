<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect value of input field
    $uid = $_POST['UserID']; // Make sure this matches your form field names
    $password = $_POST['Password']; // This should match case with your HTML form

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
    $stmt = $conn->prepare("SELECT Password, Role FROM users WHERE UserID = ?;");
    $stmt->bind_param("s", $uid); // "s" specifies the variable type => 'string'
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verifying the password
        if ($password == $row['Password']) {
            // Login successful
            echo json_encode(['status' => 'success', 'Role' => $row['Role'], 'UserID' => $uid]);
        } else {
            // Password does not match
            echo "Login failed";
        }
    } else {
        // UID not found
        echo "Login failed";
    }

    $conn->close();
}
?>
