<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $destination_id = $_GET['id'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "travel_bucket");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the current data for the destination
    $sql = "SELECT * FROM bucket_list WHERE id = $destination_id AND user_id = " . $_SESSION['user_id'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
    } else {
        echo "Destination not found.";
        exit();
    }

    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination = $_POST['destination'];
    $notes = $_POST['notes'] ?? '';

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "travel_bucket");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the destination in the database
    $sql = "UPDATE bucket_list SET destination = '$destination', notes = '$notes' WHERE id = $destination_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Edit Destination</h1>
    </header>
    <main>
        <form action="edit_destination.php?id=<?php echo $destination_id; ?>" method="POST">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" value="<?php echo htmlspecialchars($destination['destination']); ?>" required>
            
            <label for="notes">Notes (optional):</label>
            <textarea id="notes" name="notes"><?php echo htmlspecialchars($destination['notes']); ?></textarea>
            
            <button type="submit">Update Destination</button>
        </form>
    </main>
</body>
</html>

