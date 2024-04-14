<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect value of input fields
    $name = $_POST['Name'];
    $admin = $_POST['Admin'];
    $members = [$_POST['m1'], $_POST['m2'], $_POST['m3'], $_POST['m4']]; // Collect all member emails into an array

    // Database connection variables
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch admin user details
    $sql = $conn->prepare("SELECT UserID, UniversityID FROM users WHERE Email=?");
    $sql->bind_param("s", $admin);
    $sql->execute();
    $result = $sql->get_result();
    $adminRow = $result->fetch_assoc();

    if ($adminRow) {
        // Insert new RSO
        $stmt = $conn->prepare("INSERT INTO rsos (Name, AdminID, UniversityID) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $name, $adminRow['UserID'], $adminRow['UniversityID']);
        $stmt->execute();
        $rsoId = $stmt->insert_id; // Get the ID of the newly created RSO

        // Update admin role and add to RSO
        $updateRole = $conn->prepare("UPDATE users SET Role='admin' WHERE UserID=?");
        $updateRole->bind_param("i", $adminRow['UserID']);
        $updateRole->execute();

        $addToRSO = $conn->prepare("INSERT INTO memberships (UserID, RSOID, UserRole) VALUES (?, ?, 'admin')");
        $addToRSO->bind_param("ii", $adminRow['UserID'], $rsoId);
        $addToRSO->execute();

        // Process each member
        $addMemberToRSO = $conn->prepare("INSERT INTO memberships (UserID, RSOID, UserRole) VALUES (?, ?, 'member')");
        foreach ($members as $memberEmail) {
            if (!empty($memberEmail) && $memberEmail != $admin) { // Ensure the member is not the admin
                $mem = $conn->prepare("SELECT UserID FROM users WHERE Email=?");
                $mem->bind_param("s", $memberEmail);
                $mem->execute();
                $memResult = $mem->get_result();
                if ($member = $memResult->fetch_assoc()) {
                    $addMemberToRSO->bind_param("ii", $member['UserID'], $rsoId);
                    $addMemberToRSO->execute();
                }
            }
        }

        echo "New RSO created successfully, with members added.";
        $stmt->close();
        $addToRSO->close();
        $updateRole->close();
        $addMemberToRSO->close();
    } else {
        echo "Admin not found";
    }

    $conn->close();
}
?>
