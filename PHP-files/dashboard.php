<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['AlumniID'])) {
    header("Location: login.php");
    exit;
}

$alumniID = $_SESSION['AlumniID'];
$name = $_SESSION['FirstName'];  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Alumni Network</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin-top: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        h2 {
            color: #4a90e2;
            font-size: 24px;
        }
        .user-info {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        nav {
            margin-top: 20px;
        }
        nav a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #f1f9ff;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #4a90e2;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Dashboard</h2>
        <div class="user-info">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($alumniID); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
        </div>
        <p>Navigate through the options below to manage jobs, view events, and connect with other alumni.</p>
        <nav>
            <a href="post_job.php">Post a Job</a>
            <a href="view_jobs.php">View Jobs</a>
            <a href="events.php">View Events</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</body>
</html>
