<!-- login.php -->
<?php 
include 'db.php'; 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Alumni Network</title>
    <style>
        /* Styling as before */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 400px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
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
        
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-top: 10px;
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
        <h2>Alumni Login</h2>
        <form method="POST" action="login.php">
            <input type="text" name="alumniID" placeholder="Alumni ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            $alumniID = $_POST['alumniID'];
            $password = $_POST['password'];

            // Call stored procedure to get alumni credentials
            $sql = "CALL sp_getAlumniCredentials(?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $alumniID);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verify if the alumni ID exists
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                // Check if password matches
                if (password_verify($password, $row['Password'])) {
                    // Store both alumniID and name in the session
                    $_SESSION['AlumniID'] = $alumniID;
                    $_SESSION['FirstName'] = $row['FirstName']; // Store name in session

                    echo "<div class='success'>Login successful! Redirecting...</div>";
                    header("refresh:2; url=dashboard.php");
                    exit;
                } else {
                    echo "<div class='error'>Invalid password.</div>";
                }
            } else {
                echo "<div class='error'>Alumni ID not found.</div>";
            }
            $stmt->close();
        }
        ?>
        <br/>
        <button class="link-button" onclick="window.location.href='index.php'">Back to Homepage</button>
        
        <br/><br/>
        <button class="link-button" onclick="window.location.href='change_password.php'">Change Password</button>

    </div>
</body>
</html>
