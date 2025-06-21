<?php
include("includes/db.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!<br><br>";
}

// Check if the toys table exists
$table_check = $conn->query("SHOW TABLES LIKE 'toys'");
if ($table_check->num_rows == 0) {
    die("The toys table does not exist!");
}

// Get all toys from the database
$sql = "SELECT id, name, age_group, category, image FROM toys ORDER BY id DESC LIMIT 6";
$result = $conn->query($sql);

if ($result) {
    echo "Found " . $result->num_rows . " toys in the database:<br><br>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th style='padding: 8px;'>ID</th>";
        echo "<th style='padding: 8px;'>Name</th>";
        echo "<th style='padding: 8px;'>Age Group</th>";
        echo "<th style='padding: 8px;'>Category</th>";
        echo "<th style='padding: 8px;'>Image Path</th>";
        echo "</tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='padding: 8px;'>" . $row['id'] . "</td>";
            echo "<td style='padding: 8px;'>" . $row['name'] . "</td>";
            echo "<td style='padding: 8px;'>" . $row['age_group'] . "</td>";
            echo "<td style='padding: 8px;'>" . $row['category'] . "</td>";
            echo "<td style='padding: 8px;'>" . $row['image'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No toys found in the database.";
    }
} else {
    echo "Error querying the database: " . $conn->error;
}

$conn->close();
?> 