<?php
include 'db.php';

// Function to fetch and display a table with search functionality
function displayTable($conn, $tableName, $excludedColumns = []) {
    // Handle search query
    $search = isset($_GET[$tableName . '_search']) ? $_GET[$tableName . '_search'] : '';
    echo "<center><h2>" . ucfirst($tableName) . " Table</h2></center>";
    echo "<form method='GET' style='margin-bottom: 10px;'>
            <input type='text' name='{$tableName}_search' placeholder='Search in $tableName...' value='" . htmlspecialchars($search) . "'>
            <input type='submit' value='Search'>
          </form>";

    // Modify query to include search condition
    $query = "SELECT * FROM $tableName";
    if ($search) {
        $query .= " WHERE ";
        $columns = $conn->query("SHOW COLUMNS FROM $tableName");
        $conditions = [];
        while ($column = $columns->fetch_assoc()) {
            if (!in_array($column['Field'], $excludedColumns)) {
                $conditions[] = "{$column['Field']} LIKE '%$search%'";
            }
        }
        $query .= implode(" OR ", $conditions);
    }
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<div class='table-container'><table>";
        echo "<tr>";
        
        // Fetch and display column names, excluding specified columns
        $fields = [];
        while ($field = $result->fetch_field()) {
            if (!in_array($field->name, $excludedColumns)) {
                echo "<th>" . ucfirst($field->name) . "</th>";
                $fields[] = $field->name;
            }
        }
        echo "</tr>";
        
        // Fetch and display rows of data
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($fields as $field) {
                echo "<td>" . htmlspecialchars($row[$field]) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table></div>";
    } else {
        echo "<p>No records found in $tableName table.</p>";
    }
    echo "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #2d3748;
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        input[type="text"] {
            padding: 8px;
            width: 250px;
            margin-right: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 8px 15px;
            background-color: #5a67d8;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #434190;
        }
        .table-container {
            width: 90%;
            margin: 20px auto;
            overflow-x: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        th {
            background-color: #4a5568;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #f7fafc;
        }
        tr:hover {
            background-color: #edf2f7;
        }
        .link-button {
            display: block;
            width: 180px;
            margin: 20px auto;
            padding: 12px 20px;
            font-size: 1em;
            color: #ffffff;
            background-color: #5a67d8;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .link-button:hover {
            background-color: #434190;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <a href="index.php" class="link-button">Back to Homepage</a>

    <!-- Display each table with specific excluded columns -->
    <?php
    displayTable($conn, "alumni", ["Password"]); // Hides the "password" column for alumni table
    displayTable($conn, "registrations");
    displayTable($conn, "events");
    displayTable($conn, "job_postings");
    ?>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
