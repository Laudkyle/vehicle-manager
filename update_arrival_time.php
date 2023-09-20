<?php
// Include your database connection code
require 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the ticket ID from the JSON payload
    $data = json_decode(file_get_contents("php://input"));
    $ticketId = $data->ticket_id;

    // Set the default timezone to UTC
    date_default_timezone_set('UTC');

    // Get the current timestamp in UTC
    $arrivalTime = date('Y-m-d H:i:s');

    // Update the arrival_time in the database for the specified ticket ID
    $sql = "UPDATE bookings SET arrival_time = '$arrivalTime' WHERE ticket = '$ticketId'";

    if ($con->query($sql) === TRUE) {
        // Arrival time updated successfully
        $response = array("success" => true);
        echo json_encode($response);
    } else {
        // Error updating arrival time
        $response = array("success" => false);
        echo json_encode($response);
    }

    // Close the database connection
    $con->close();
} else {
    // Handle invalid request method
    http_response_code(405); // Method Not Allowed
}
?>
