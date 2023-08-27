<?php 
        $conn =  mysqli_connect("localhost", "root", "", "sensor_db");
        $query = mysqli_query ($conn, "SELECT notif FROM dht11 ORDER BY id DESC");  
        $row = mysqli_fetch_assoc($query);
        $status = (int)$row['notif'];
?>

<?php 
    if ($status === 1){
        // echo "<script>alert('Barang siap diambil');</script>";
        echo "ON";
    }
    else {
        echo "OFF";
    }
?>