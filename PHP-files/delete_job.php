<?php
session_start();
include 'db.php';

$userID = $_SESSION['AlumniID'] ?? null; 
$jobID = isset($_GET['JobID']) ? (int)$_GET['JobID'] : null; 

if ($userID && $jobID) {
    $checkSql = "SELECT PostedBy FROM JOB_POSTINGS WHERE JobID = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("i", $jobID);
    $stmt->execute();
    $stmt->bind_result($postedBy);
    $stmt->fetch();
    $stmt->close();

    if ($postedBy === $userID) {
        $deleteSql = "DELETE FROM JOB_POSTINGS WHERE JobID = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $jobID);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<p class='success'>Job deleted successfully.</p>";
        } else {
            $_SESSION['message'] = "<p class='error'>Error: Unable to delete job.</p>";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "<p class='error'>Error: Unauthorized action.</p>";
    }
} else {
    $_SESSION['message'] = "<p class='error'>Error: Invalid request.</p>";
}

$conn->close();
header("Location: view_jobs.php");
exit;
?>
