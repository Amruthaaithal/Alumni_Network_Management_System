<!-- change_password.php -->
<?php 
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $alumniID = $_POST['alumniID'];
    $newPassword = $_POST['new_password'];

    // Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update new password in the database
    $updateSql = "CALL sp_updateAlumniPassword(?, ?)";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ss", $alumniID, $hashedNewPassword);

    if ($updateStmt->execute()) {
        $message = "<div class='success'>Password updated successfully!</div>";
    } else {
        $message = "<div class='error'>Error updating password. Please try again.</div>";
    }
    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Alumni Network</title>
    <style>
        /* Styling as before */
        .container {
            width: 400px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h2, .success, .error {
            text-align: center;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 24px);
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .link-button {
            display: inline-block;
            padding: 10px 15px;
            font-size: 1em;
            color: #ffffff;
            background-color: #5a67d8; /* Same color as the register button */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none; /* Remove underline */
            text-align: center;
            transition: background-color 0.3s;
            width: 100%; /* Full width button */
            box-sizing: border-box; /* Include padding in width */
        }
        .link-button:hover {
            background-color: #434190; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form method="POST" action="">
            <input type="text" name="alumniID" placeholder="Alumni ID" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="change_password">Update Password</button>
        </form>
        <?php if (isset($message)) echo $message; ?>
        <br/>
        <button class="link-button" onclick="window.location.href='index.php'">Back to Homepage</button>
    </div>
</body>
</html>
