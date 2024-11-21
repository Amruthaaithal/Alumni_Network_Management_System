<?php
session_start();
include 'db.php';


$userID = $_SESSION['AlumniID'] ?? null;
$sql = "SELECT * FROM JOB_POSTINGS ORDER BY PostedDate DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Jobs</title>
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
            max-width: 800px;
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
        .job-posting {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .job-posting h3 {
            color: #007bff;
            margin: 0;
        }
        .job-posting p {
            color: #333333;
            margin: 5px 0;
        }
        .apply-button {
            display: inline-block;
            padding: 10px 15px;
            font-size: 1em;
            color: #ffffff;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .apply-button:hover {
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
        .delete-button {
    display: inline-block;
    padding: 10px 15px;
    font-size: 1em;
    color: #ffffff;
    background-color: #dc3545; /* Red for delete */
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s;
    margin-left: 10px;
}
.delete-button:hover {
    background-color: #c82333;
}

    </style>
    <script>
        function confirmDelete(jobID) {
    if (confirm("Are you sure you want to delete this job posting?")) {
        window.location.href = "delete_job.php?JobID=" + jobID;
    }
}

    </script>
</head>
<body>

<div class="container">
    <h2>Job Postings</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="job-posting">
                <h3><?php echo htmlspecialchars($row['Title']); ?></h3>
                <p><strong>Company:</strong> <?php echo htmlspecialchars($row['Company']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['Location']); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($row['Description'])); ?></p>
                <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($row['Requirements'])); ?></p>
                <p><strong>Posted Date:</strong> <?php echo htmlspecialchars($row['PostedDate']); ?></p>
                <p><strong>Deadline:</strong> <?php echo htmlspecialchars($row['Deadline']); ?></p>
                <a href="<?php echo htmlspecialchars($row['ApplicationLink']); ?>" target="_blank" class="apply-button">Apply</a>
                <?php if ($userID === $row['PostedBy']): ?>
                    <button onclick="confirmDelete('<?php echo $row['JobID']; ?>')" class="delete-button">Delete</button>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No job postings available.</p>
    <?php endif; ?>
    
    <br>
    <button class="link-button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
</div>

</body>
</html>

<?php $conn->close(); ?>
