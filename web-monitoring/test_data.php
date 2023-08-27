<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "sensor_db";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Could not connect to the database" . mysqli_connect_error());
}

echo "Database OK<br>";

// Menampilkan data yang diterima dari perangkat ESP8266
if (isset($_POST["time"]) && isset($_POST["sector"]) && isset($_POST["notif"])) {
    $time = $_POST["time"];
    $sector = $_POST["sector"];
    $notif = $_POST["notif"];

    echo "Time: " . $time . "<br>";
    echo "Sector: " . $sector . "<br>";
    echo "Notif: " . $notif . "<br>";

    // Simpan data ke dalam database
    $sql = "INSERT INTO dht11 (time, sector, notif) VALUES ('$time', '$sector', '$notif')";
    if (mysqli_query($conn, $sql)) {
        echo "Data inserted successfully";
    } else {
        echo "ERROR: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>
