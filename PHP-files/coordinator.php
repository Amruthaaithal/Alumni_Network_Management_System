<?php 
include 'db.php'; 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['AlumniID'])) {
    header("Location: coord_login.php");
    exit;
}

// Retrieve the coordinator's AlumniID
$alumniID = $_SESSION['AlumniID'];

// Query to get events organized by the logged-in coordinator with associated registration counts
$sql = "SELECT e.EventID, e.EventName, COUNT(r.RegistrationID) AS TotalRegistrations 
        FROM EVENTS e
        LEFT JOIN REGISTRATIONS r ON e.EventID = r.EventID
        WHERE e.OrganiserID = ?
        GROUP BY e.EventID
        ORDER BY e.EventID";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $alumniID);
$stmt->execute();
$result = $stmt->get_result();

// Organize events and retrieve registrations separately
$events = [];
while ($row = $result->fetch_assoc()) {
    $eventID = $row['EventID'];
    $events[$eventID] = [
        'EventName' => $row['EventName'],
        'TotalRegistrations' => $row['TotalRegistrations'],
        'Registrations' => []
    ];
}

// Query to get detailed registrations for each event
$sqlRegistrations = "SELECT e.EventID, r.RegistrationID, r.AlumniID, r.RegistrationDate 
                     FROM EVENTS e
                     LEFT JOIN REGISTRATIONS r ON e.EventID = r.EventID
                     WHERE e.OrganiserID = ?
                     ORDER BY e.EventID, r.RegistrationDate";
$stmtRegistrations = $conn->prepare($sqlRegistrations);
$stmtRegistrations->bind_param("s", $alumniID);
$stmtRegistrations->execute();
$registrationsResult = $stmtRegistrations->get_result();

while ($registration = $registrationsResult->fetch_assoc()) {
    $eventID = $registration['EventID'];
    if ($registration['RegistrationID']) {
        $events[$eventID]['Registrations'][] = $registration;
    }
}

$stmt->close();
$stmtRegistrations->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator Dashboard - Event Registrations</title>
    <style>
        /* Styling as before */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        h3 {
            color: #006400;
            border-bottom: 2px solid #28a745;
            padding-bottom: 5px;
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        .no-registrations {
            text-align: center;
            color: #777;
        }
        .logout {
            display: inline-block;
            font-size: 1em;
            border: none;
            cursor: pointer;
            text-align: center;
            margin-top: -10px;
            color: #4a90e2;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #f1f9ff;
            transition: background-color 0.3s;
        }
        .logout:hover {
            background-color: #4a90e2;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['FirstName']); ?> - Your Event Registrations</h2>
        <a href="logout.php" class="logout">Logout</a>

        <?php foreach ($events as $eventID => $eventData): ?>
            <h3><?php echo htmlspecialchars($eventData['EventName']); ?></h3>
            <p>Total Registrations: <?php echo $eventData['TotalRegistrations']; ?></p>
            
            <?php if (!empty($eventData['Registrations'])): ?>
                <table>
                    <tr>
                        <th>Registration ID</th>
                        <th>Alumni ID</th>
                        <th>Registration Date</th>
                    </tr>
                    <?php foreach ($eventData['Registrations'] as $registration): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registration['RegistrationID']); ?></td>
                            <td><?php echo htmlspecialchars($registration['AlumniID']); ?></td>
                            <td><?php echo htmlspecialchars($registration['RegistrationDate']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="no-registrations">No registrations found for this event.</p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>
