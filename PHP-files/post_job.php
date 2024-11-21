<?php
session_start();
include 'db.php';

$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $requirements = $_POST['requirements'];
    $applicationLink = $_POST['application_link'];
    $postedBy = $_SESSION['AlumniID'] ?? null;  
    $postedDate = date("Y-m-d");
    $deadline = $_POST['deadline'];

    if ($postedBy) { 
        $sql = "INSERT INTO JOB_POSTINGS (Title, Description, Company, Location, Requirements, ApplicationLink, PostedBy, PostedDate, Deadline)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $title, $description, $company, $location, $requirements, $applicationLink, $postedBy, $postedDate, $deadline);
        
        if ($stmt->execute()) {
            $message = "<p class='success'>Job posted successfully!</p>";
        } else {
            $message = "<p class='error'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p class='error'>Error: User not logged in.</p>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 90vh;
            overflow-y: auto;
        }
        h2 {
            text-align: center;
            color: #333333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333333;
        }
        input[type="text"], textarea, input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            color: #28a745;
            text-align: center;
        }
        .error {
            color: #dc3545;
            text-align: center;
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
    <h2>Post a Job</h2>
    
    <?php echo $message; ?>

    <form action="" method="POST">
    <label for="title">Job Title</label>
    <input type="text" id="title" name="title" required>
    
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4" required></textarea>
    
    <label for="company">Company</label>
    <input type="text" id="company" name="company" required>
    
    <label for="location">Location</label>
    <input type="text" id="location" name="location" required>
    
    <label for="requirements">Requirements</label>
    <textarea id="requirements" name="requirements" rows="3" required></textarea>
    
    <label for="application_link">Application Link</label>
    <input type="text" id="application_link" name="application_link" required>

    <label for="deadline">Deadline</label>
    <input type="date" id="deadline" name="deadline" required>
    
    <button type="submit">Post Job</button>
</form>

    <br/>
        <button class="link-button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
</div>

</body>
</html>
