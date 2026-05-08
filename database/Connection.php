<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "norsu_system";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>