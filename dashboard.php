<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Your Travel Bucket List</h2>
        <form action="add_destination.php" method="POST">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required>
            
            <label for="notes">Notes (optional):</label>
            <textarea id="notes" name="notes"></textarea>
            
            <button type="submit">Add to Bucket List</button>
        </form>
        <hr>
        <h3>My Bucket List:</h3>
        <ul>
        
    <?php
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "travel_bucket");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM bucket_list WHERE user_id = $user_id";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        echo "<form action='mark_visited.php' method='POST'>";
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
        $destination = $row['destination'];
        $notes = $row['notes'];
        $visited = $row['visited'];

        if ($visited == 1) {
            echo "<div style='text-decoration: line-through; color: gray;'>";
        } else {
            echo "<div>";
        }

        echo "<input type='checkbox' name='visited[]' value='$id'> "; // Checkbox for each destination
        if ($visited == 1) echo " checked"; // Make sure to pre-check visited items
        echo "> ";
        echo "<strong>$destination</strong><br>";
        echo "<small>Notes: $notes</small><br>";
            echo "<li>" . htmlspecialchars($row['destination']) . 
                 (!empty($row['notes']) ? " - " . htmlspecialchars($row['notes']) : "") . 
                 " <a href='edit_destination.php?id=" . $row['id'] . "'>Edit</a> | 
                 <a href='delete_destination.php?id=" . $row['id'] . "'>Delete</a>
                 <a href='update_destination.php?id=" . $row['id'] . "'>Update</a> </li>";
        }
        echo "<button type='submit'>Mark Visited</button>";
        echo "</form>";
    } else {
        echo "<p>You have no destinations in your bucket list yet.</p>";
    }

    $conn->close();
    ?>


        </ul>
    </main>
</body>
</html>
