<?php

$conn = mysqli_connect("localhost", "root", "", "sensor_db");

$result = mysqli_query ($conn, "SELECT*FROM dht11");
// $sector = mysqli_query($conn, "SELECT time FROM dht11 ORDER BY sector DESC");
// $time = mysqli_query($conn, "SELECT time FROM dht11 ORDER BY time DESC");
?>
<!-- 
<div class="panel panel-primary">
    <div class="panel-heading">
        Data
    </div>

</div>
?> -->