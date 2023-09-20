<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect the user to the login page
    header('Location: auth.php');
    exit;
}
// Include your database connection file here
require 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($con, $id);

    // Query the database to retrieve the entry with the specified ID
    $sql = "SELECT * FROM bookings WHERE id = $id";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Encode the row data as JSON
        $jsonResponse = json_encode($row);
        echo $jsonResponse;
        
    } else {
        echo json_encode(array("error" => "Entry not found"));
    }
} else {
    echo json_encode(array("error" => "Invalid request"));
}
?>
