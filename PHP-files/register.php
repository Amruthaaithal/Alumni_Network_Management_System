<!-- register.php -->
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Alumni Network</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"],
        input[type="password"],
        input[type="number"] {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus {
            border-color: #5a67d8;
            outline: none;
        }
        button {
            padding: 10px;
            font-size: 1em;
            color: #fff;
            background-color: #5a67d8;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #434190;
        }
        .feedback {
            margin-top: 15px;
            font-size: 0.9em;
        }
        .feedback.success {
            color: #28a745;
        }
        .feedback.error {
            color: #d9534f;
        }
        .login-link {
            margin-top: 20px;
            text-align: center;
        }
        .login-link a {
            color: #5a67d8;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
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
        <h2>Alumni Registration</h2>
        <form method="POST" action="register.php">
            <input type="text" name="alumniId" placeholder="Alumni ID (Unique)" required>
            <input type="text" name="firstName" placeholder="First Name" required>
            <input type="text" name="lastName" placeholder="Last Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="company" placeholder="Company">
            <input type="text" name="degree" placeholder="Degree">
            <input type="number" name="graduationYear" placeholder="Graduation Year">
            <input type="text" name="currentJob" placeholder="Current Job">
            <input type="text" name="socialLinks" placeholder="Social Links">
            <button type="submit" name="register">Register</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $alumniId = $_POST['alumniId'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $company = $_POST['company'];
            $degree = $_POST['degree'];
            $graduationYear = $_POST['graduationYear'];
            $currentJob = $_POST['currentJob'];
            $socialLinks = $_POST['socialLinks'];

            // Check if Alumni ID is unique using stored procedure
            $checkStmt = $conn->prepare("CALL sp_check_alumni_id(?)");
            $checkStmt->bind_param("s", $alumniId);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($count > 0) {
                echo "<div class='feedback error'>Error: Alumni ID already exists. Please choose another one.</div>";
            } else {
                // Register new alumni using stored procedure
                $stmt = $conn->prepare("CALL sp_register_alumni(?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssiss", $alumniId, $firstName, $lastName, $password, $company, $degree, $graduationYear, $currentJob, $socialLinks);
                
                if ($stmt->execute()) {
                    echo "<div class='feedback success'>Registration successful!</div>";
                    echo "<div class='login-link'><a href='login.php'>Click here to login</a></div>";
                } else {
                    echo "<div class='feedback error'>Error: " . $stmt->error . "</div>";
                }
                $stmt->close();
            }
            $conn->close();
        }
        ?>
        <br/>
        <button class="link-button" onclick="window.location.href='index.php'">Back to Homepage</button>
    </div>
</body>
</html>
