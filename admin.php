<?php
session_start();

// Check if the user is logged in
if ((!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) || $_SESSION['admin'] != 1) {
    // Redirect the user to the login page
    header('Location: auth.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Home</title>

</head>

<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="bookings.php">Bookings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h1>Book A Vehicle</h1>
    <form action="#" method="post">

        <label for="time">Time of Departure:</label>
        <input type="time" id="time" name="time" required><br><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" placeholder="Where are you going" required><br><br>

        <label for="purpose">Purpose of Trip:</label>
        <textarea rows="12" cols="50" id="purpose" name="purpose" placeholder="The purpose of your trip"
            required></textarea><br><br>


        <input type="submit" value="Submit">
        <?php
        include "connection.php";
        include "mail.php";
        $userIP = $_SERVER['REMOTE_ADDR'];
        $dept_id = $_SESSION['department_id'];
        $name = $_SESSION['name'];
        $position = $_SESSION['position'];
        $phone = $_SESSION['phone'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $time = mysqli_real_escape_string($con, $_POST['time']);
            $location = mysqli_real_escape_string($con, $_POST['location']);
            $purpose = mysqli_real_escape_string($con, $_POST['purpose']);

            $pre_sql = "SELECT department_name FROM departments WHERE id= $dept_id";
            $result = $con->query($pre_sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $dept = $row['department_name'];
            }


            // SQL query to insert data into the "license_entries" table
            $sql = "INSERT INTO `bookings` (`id`,`name`,`time`,`location`,`purpose`,`department_id`,`position`,`phone`,`ip`) VALUES (NULL,'$name','$time','$location','$purpose','$dept_id','$position','$phone','$userIP');";

            if ($con->query($sql)) {
                sendEmail('kyleaby1@gmail.com', $name, $time, $location, $dept, $position, $purpose, $phone);

                echo "<p style='text-align: center; color: green;'>Your request has been submitted, you will be updated promptly</p>";
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        }
        ?>
    </form>
    </div>
</body>

</html>