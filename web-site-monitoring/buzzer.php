<?php 
    function getStatusBuzzer() {
        $conn =  mysqli_connect("localhost", "root", "", "sensor_db");
        $query = mysqli_query ($conn, "SELECT statusBuzzer FROM dht11");  
        $row = mysqli_fetch_assoc($query);
        $status = (int)$row['statusBuzzer'];

        return $status;
    }

    function setStatusBuzzer($status) {
        $conn =  mysqli_connect("localhost", "root", "", "sensor_db");
        $query = "UPDATE dht11 SET statusBuzzer=$status";  
        mysqli_query($conn, $query);
    }

    $statusBuzzer = getStatusBuzzer();

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
      
        if ($action === 'on') {
          $statusBuzzer = 1; // 1 untuk menyala (ON)
          setStatusBuzzer($statusBuzzer);
        } elseif ($action === 'off') {
          $statusBuzzer = 0; // 0 untuk mati (OFF)
          setStatusBuzzer($statusBuzzer);
        }
    }
?>

<?php 
    if ($statusBuzzer === 1){
        echo "ON";
    }
    else {
        echo "OFF";
    }
?>