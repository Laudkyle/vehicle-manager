<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional CSS for ticket details formatting */
        .ticket-details {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 20px auto;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .ticket-details p {
            margin: 10px 0;
            font-size: 16px;
        }

        .ticket-details strong {
            font-weight: bold;
            color: #530045;
        }

        .ticket-details hr {
            border-top: 1px solid #ddd;
        }
    </style>
    <title>Ride Details</title>
</head>

<body>
    <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h1>Ride Details</h1>
    <div id="get-details-container"> <!-- Container for "Get Details" div -->
        <form action="#" method="get" id="ride-details-form">
            <label for="ticket_id">Enter Ticket ID:</label>
            <input type="text" id="ticket_id" name="ticket_id" required>
            <input type="submit" value="Get Details">
        </form>
    </div>

    <?php
    session_start();

    // Check if the user is logged in (you can adjust this based on your authentication logic)
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Redirect the user to the login page
        header('Location: auth.php');
        exit;
    }

    // Check if the ticket ID is provided via GET request
    if (isset($_GET['ticket_id'])) {
        $ticketId = $_GET['ticket_id'];


        // Include your database connection code
        require 'connection.php';

        // Query to retrieve ride details based on the ticket ID
        $sql = "SELECT * FROM bookings WHERE ticket = '$ticketId'";
        $result = $con->query($sql);

        // Check if a ride with the provided ticket ID exists
        if ($result->num_rows > 0) {
            // Fetch ride details
            $row = $result->fetch_assoc();

            // Output the ride details with improved formatting
            echo "<div class='ticket-details'>";
            echo "<p><strong>Name:</strong> " . $row['name'] . "</p>";
            echo "<p><strong>Time:</strong> " . $row['time'] . "</p>";
            echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
            echo "<p><strong>Purpose:</strong> " . $row['purpose'] . "</p>";
            echo "<p><strong>Position:</strong> " . $row['position'] . "</p>";
            echo "<p><strong>Phone:</strong> " . $row['phone'] . "</p>";
            echo "<p><strong>Car:</strong> " . $row['car'] . "</p>";
            echo "<p><strong>Driver:</strong> " . $row['driver'] . "</p>";
            echo "<hr>";
            echo "<button class='btn' id='confirm-button'>Confirm and Sign</button>";
            echo "</div>";
          
          echo "<script>";
            // Save ticket details to a temporary JSON variable
          echo "var ticketDetails = {ticket_id: '$ticketId'} </script>";
          
        } else {
            echo "<p>No ride found with the provided ticket ID.</p>";
        }
    }
    ?>
</body>

<script>
    // Function to enable buttons when ticket details are loaded
    function enableButtons() {
        document.getElementById('confirm-button').style.display = 'inline-block';
        document.getElementById('get-details-container').style.display = 'none';

    }

    // Function to handle the Confirm button click event
    document.getElementById('confirm-button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the form from submitting traditionally

        // Get ticket ID from the saved JSON variable
        const ticketId = ticketDetails.ticket_id;
        // Perform an AJAX request to update arrival time
        fetch('update_arrival_time.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ticket_id: ticketId }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Arrival time updated successfully
                    alert('Arrival time updated successfully.');
                } else {
                    alert('Error updating arrival time.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });

    // Enable buttons when ticket details are loaded
    enableButtons();
</script>

</html>
