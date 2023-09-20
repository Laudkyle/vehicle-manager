<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname= "licenses";

$con = new mysqli($server,$username,$password, $dbname);

        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }
?>