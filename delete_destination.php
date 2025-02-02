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

    // Delete the destination from the database
    $sql = "DELETE FROM bucket_list WHERE id = $destination_id AND user_id = " . $_SESSION['user_id'];

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}
?>
