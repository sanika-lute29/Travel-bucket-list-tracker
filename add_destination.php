<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $destination = $_POST['destination'];
    $notes = $_POST['notes'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "travel_bucket");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the destination into the bucket list table
    $sql = "INSERT INTO bucket_list (user_id, destination, notes) VALUES ('$user_id', '$destination', '$notes')";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: login.html");
    exit();
}
?>
