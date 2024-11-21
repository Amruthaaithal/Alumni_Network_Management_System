<?php
session_start();
require_once 'db.php';  

// Check if alumni is logged in
if (!isset($_SESSION['AlumniID'])) {
    echo "Please log in to view and manage events.";
    exit;
}

$alumni_id = $_SESSION['AlumniID'];

// Handle event posting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    $eventName = $_POST['event_name'];
    $eventType = $_POST['event_type'];
    $eventDescription = $_POST['event_description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    // Start a transaction to ensure both queries succeed or fail together
    $conn->begin_transaction();

    try {
        // Insert event into EVENTS table
        $query = "INSERT INTO EVENTS (EventName, EventType, EventDescription, Date, Time, Location, OrganiserID) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $eventName, $eventType, $eventDescription, $date, $time, $location, $alumni_id);

        if (!$stmt->execute()) {
            throw new Exception("Error creating event: " . $stmt->error);
        }

        // Commit the transaction
        $conn->commit();
        echo "<div class='success'>Event created successfully! You have been added as the coordinator.</div>";

    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo "<div class='error'>" . $e->getMessage() . "</div>";
    }

    $stmt->close();
}

// Handle event registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];

    $check_query = "SELECT * FROM REGISTRATIONS WHERE EventID = ? AND AlumniID = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("is", $event_id, $alumni_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='error'>You are already registered for this event!</div>";
    } else {
        // Insert registration into REGISTRATIONS table
        $query = "INSERT INTO REGISTRATIONS (EventID, AlumniID, RegistrationDate) VALUES (?, ?, CURDATE())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $event_id, $alumni_id);

        if ($stmt->execute()) {
            echo "<div class='success'>Registered successfully for the event!</div>";
        } else {
            echo "<div class='error'>Error registering for event: </div>" . $conn->error;
        }
        $stmt->close();
    }
    $check_stmt->close();

}

// Fetch all events
$events = $conn->query("SELECT * FROM EVENTS ORDER BY Date, Time");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #333;
        }
        .events-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .event-card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            width: 300px;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }
        .event-card h3 {
            color: #444;
            margin-top: 0;
        }
        .event-details {
            color: #666;
            margin: 10px 0;
        }
        .event-details p {
            margin: 5px 0;
        }
        .register-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .register-btn:hover {
            background-color: #45a049;
        }
        .new-event-form {
            width: 400px;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin: 20px;
        }
        .new-event-form label {
            display: block;
            margin: 10px 0 5px;
        }
        .new-event-form input, .new-event-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .create-btn {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
        }
        .create-btn:hover {
            background-color: #0069d9;
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
    </style>
</head>
<body>

<h1>Available Events</h1>

<div class="events-container">
    <?php while ($event = $events->fetch_assoc()): ?>
        <div class="event-card">
            <h3><?php echo htmlspecialchars($event['EventName']); ?></h3>
            <div class="event-details">
                <p><?php echo htmlspecialchars($event['EventDescription']); ?></p>
                <br>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($event['EventType']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($event['Date']); ?></p>
                <p><strong>Time:</strong> <?php echo htmlspecialchars($event['Time']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['Location']); ?></p>
                
            </div>
            <form method="post">
                <input type="hidden" name="event_id" value="<?php echo $event['EventID']; ?>">
                <button type="submit" name="register_event" class="register-btn">Register</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>

<h2>Organise a New Event</h2>
<div class="new-event-form">
    <form method="post">
        <label>Event Name: <input type="text" name="event_name" required></label>
        <label>Type: <input type="text" name="event_type" required></label>
        <label>Description: <textarea name="event_description" required></textarea></label>
        <label>Date: <input type="date" name="date" required></label>
        <label>Time: <input type="time" name="time" required></label>
        <label>Location: <input type="text" name="location" required></label>
        <button type="submit" name="create_event" class="create-btn">Create Event</button>
    </form>
    <br>
    <button class="link-button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>

</div>

</body>
</html>

<?php
$conn->close();
?>
