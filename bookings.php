<?php
session_start();

// Check if the user is logged in
if ((!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) || $_SESSION['admin'] != 1) {
    // Redirect the user to the login page
    header('Location: auth.php');
    exit;
}

$userIP = $_SERVER['REMOTE_ADDR'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <!-- <script src="script.js"></script> -->
    <title>Bookings</title>
</head>

<body>
    <nav>
        <ul>
            <li><a href="admin.php">Home</a></li>
            <li><a href="bookings.php">Bookings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <h1>Bookings</h1>

    <?php
    $uniqueId = uniqid("BPSL-");
    require 'connection.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_entry"])) {
        $id = $_POST["entry_id"];
        $time = $_POST['time'];
        $car = $_POST['car'];
        $driver = $_POST['driver'];

        $phone_sql = "SELECT * from users Where id = '$driver'";
        $phoneResult = $con->query($phone_sql);
        if ($phoneResult->num_rows > 0) {
            while ($row = $phoneResult->fetch_assoc()) {
                $phone = $row['phone'];
                $driverName = $row['first_name'] . ' ' . $row['last_name'];
            }
        }


        $update_sql = "UPDATE bookings SET `time`='$time', `driver`='$driverName', `car`='$car',`ticket`='$uniqueId' WHERE id=$id";
        $resultUpdate = $con->query($update_sql);
        if ($resultUpdate) {

            echo "<p style='color: green;'>Entry updated successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error updating entry: " . $con->error . "</p>";
        }
    }


    // Handle form submission for deleting entries
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_entry"])) {
        $id = $_POST["entry_id"];


        // Now, delete the entry from the "bookings" table
        $deleteSql = "DELETE FROM bookings WHERE id=$id";
        if ($con->query($deleteSql)) {
            echo "<p style='color: green;'>Entry deleted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error deleting entry: " . $con->error . "</p>";
        }
    }

    // Query the database to retrieve entries from the "bookings" table
    $sql = "SELECT * FROM bookings";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        // Display the table header
        echo "<table>";
        echo "<tr><th>Name</th><th>Time</th><th>Location</th><th>Purpose</th><th>Position</th><th>Phone</th><th>Car</th><th>Driver</th><th>Arrival Time</th><th>Action</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='name' data-id='" . $row["id"] . "'>" . $row["name"] . "</td>";
            echo "<td>" . $row["time"] . "</td>";
            echo "<td>" . $row["location"] . "</td>";
            echo "<td>" . $row["purpose"] . "</td>";
            echo "<td>" . $row["position"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "<td>" . $row["car"] . "</td>";
            echo "<td>" . $row['driver'] . "</td>";
            echo "<td>" . $row["arrival_time"] . "</td>";
            echo "<td><input type='submit' value='Update' class='update-button' data-id='" . $row["id"] . "'></td>";
            echo "</tr>";
        }
        // Close the table
        echo "</table>";
    } else {
        echo "<p>No entries found in the database.</p>";
    }

    // Close the database connection
    $con->close();
    ?>

    <!-- Modal -->
    <div id="update-modal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h2>Update Booking</h2>
            <form action="#" method="post">
                <input type="hidden" id="entry_id" name="entry_id">
                <label for="name">Name</label>
                <input type="text" id="update_name" name="name" required><br><br>

                <label for="time">Time</label>
                <input type="text" id="update_time" name="time" required><br><br>

                <label for="location">Location</label>
                <input type="text" id="update_location" name="location" required><br><br>

                <label for="purpose">Purpose</label>
                <input type="text" id="update_purpose" name="purpose" required><br><br>

                <!-- <label for="department_id">Department ID</label> -->
                <input type="hidden" id="update_department_id" name="department_id" required>

                <!-- <label for="position">Position</label> -->
                <input type="hidden" id="update_position" name="position" required>

                <label for="phone">Phone</label>
                <input type="text" id="update_phone" name="phone" required><br><br>

                <!-- <label for="ip">IP</label> -->
                <input type="hidden" id="update_ip" name="ip" required>

                <label for="car">Car</label>
                <select id="update_car" name="car" required>
                    <option value="KIA RI--GT 7102-16">KIA RI--GT 7102-16</option>
                    <option value="NISSAN HARD-BOD--GN 4306-17">NISSAN HARD-BOD--GN 4306-17</option>
                    <option value="LADA VESTA--GC 6024-20">LADA VESTA--GC 6024-20</option>
                    <option value="NISSAN HARD-BODY--GN 445-16">NISSAN HARD-BODY--GN 445-16</option>
                    <option value="TOYOTA HILUX-1st BULLION / LADA VESTA--GS 6959-22">TOYOTA HILUX-1st BULLION / LADA
                        VESTA--GS 6959-22</option>
                    <option value="TOYOTA HILUX-1st BULLION / LADA VESTA--GC 6025-20">TOYOTA HILUX-1st BULLION / LADA
                        VESTA--GC 6025-20</option>
                    <option value="NISSAN HARDBODY-2nd BULLION /NISSAN HARDBODY-PICK-UP--GT 4435-21">NISSAN HARDBODY-2nd
                        BULLION /NISSAN HARDBODY-PICK-UP--GT 4435-21</option>
                    <option value="NISSAN HARDBODY-2nd BULLION /NISSAN HARDBODY-PICK-UP--GM 4346-14">NISSAN HARDBODY-2nd
                        BULLION /NISSAN HARDBODY-PICK-UP--GM 4346-14</option>
                    <option value="LADA VESTA / TOYOTA HILUX-1st BULLION--GC 6025-20">LADA VESTA / TOYOTA HILUX-1st
                        BULLION--GC 6025-20</option>
                    <option value="LADA VESTA / TOYOTA HILUX-1st BULLION--GS 6959-22">LADA VESTA / TOYOTA HILUX-1st
                        BULLION--GS 6959-22</option>
                    <option value="KIA RIO--GT 7101-16">KIA RIO--GT 7101-16</option>
                    <option value="YAMAHA MOTOR BIKE--M 21-GT 1601">YAMAHA MOTOR BIKE--M 21-GT 1601</option>
                    <option value="HONDA MOTOR BIKE--M 23-GD 7440">HONDA MOTOR BIKE--M 23-GD 7440</option>
                    <option value="YAMAHA MOTOR BIKE--M 19-GT 3366">YAMAHA MOTOR BIKE--M 19-GT 3366</option>
                    <option value="TOYOTA FOTUNER--GT 8109-17">TOYOTA FOTUNER--GT 8109-17</option>
                    <option value="KIA CERATO--GN 3770-14">KIA CERATO--GN 3770-14</option>
                    <option value="KIA PICANTO--GN 2706-15">KIA PICANTO--GN 2706-15</option>
                </select>
                <br><br>


                <label for="driver">Driver</label>
                <select id="update_driver" name="driver" required>
                    <?php
                    // Database connection code (replace with your database credentials)
                    require 'connection.php';

                    // Query to fetch driver data from the database
                    $driverQuery = "SELECT id, first_name,last_name,phone FROM users WHERE role_id = 101";

                    // Execute the query
                    $driverResult = $con->query($driverQuery);

                    // Check if there are rows in the result
                    if ($driverResult->num_rows > 0) {
                        while ($row = $driverResult->fetch_assoc()) {
                            // Populate the <option> elements with data from the "drivers" table
                            echo "<option value='" . $row["id"] . "'>" . $row["first_name"] . " " . $row["last_name"] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No drivers available</option>";
                    }
                    // Close the database connection
                    $con->close();
                    ?>
                </select><br><br>

                </select><br><br>

                <input type="hidden" id="update_arrival_time" name="arrival_time" required>

                <input type="submit" name="update_entry" value="Update">
            </form>
        </div>
    </div>

    <script>
        // Function to open the modal
        function openModal() {
            const modal = document.getElementById('update-modal');
            modal.style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('update-modal');
            modal.style.display = 'none';
        }

        // Get all name cells and update buttons
        const nameCells = document.querySelectorAll('.name');
        const updateButtons = document.querySelectorAll('.update-button');

        // Get the update form elements
        const entryIdInput = document.getElementById('entry_id');
        const updateNameInput = document.getElementById('update_name');
        const updateTimeInput = document.getElementById('update_time');
        const updateLocationInput = document.getElementById('update_location');
        const updatePurposeInput = document.getElementById('update_purpose');
        const updateDepartmentIdInput = document.getElementById('update_department_id');
        const updatePositionInput = document.getElementById('update_position');
        const updatePhoneInput = document.getElementById('update_phone');
        const updateIpInput = document.getElementById('update_ip');
        const updateCarInput = document.getElementById('update_car');
        const updateDriverInput = document.getElementById('update_driver');
        const updateArrivalTimeInput = document.getElementById('update_arrival_time');

        // Event listener for the "Update" button
        updateButtons.forEach((updateButton) => {
            updateButton.addEventListener('click', () => {
                const entryId = updateButton.getAttribute('data-id');

                fetch('get_entry.php?id=' + entryId)
                    .then((response) => response.json())
                    .then((data) => {
                        // Populate the update form with the entry data
                        entryIdInput.value = data.id;
                        updateNameInput.value = data.name;
                        updateTimeInput.value = data.time;
                        updateLocationInput.value = data.location;
                        updatePurposeInput.value = data.purpose;
                        updateDepartmentIdInput.value = data.department_id;
                        updatePositionInput.value = data.position;
                        updatePhoneInput.value = data.phone;
                        updateIpInput.value = data.ip;
                        updateCarInput.value = data.car;
                        updateDriverInput.value = data.driver;
                        updateArrivalTimeInput.value = data.arrival_time;

                        // Open the modal
                        openModal();
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
</body>

</html>