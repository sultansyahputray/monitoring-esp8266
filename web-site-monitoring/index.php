<?php

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}



require 'db_connect.php';

?>

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

    if (isset($_GET['delete'])){
        $delete = $_GET['delete'];

        if ($delete ==='sector1') {
            $sql = "DELETE FROM dht11 WHERE sector = 1";
            $sukses1 = mysqli_query($conn, $sql);
            if ($sukses1){
                header("refresh:0.5;url=index.php");
            }
        }

        if ($delete ==='sector2') {
            $sql = "DELETE FROM dht11 WHERE sector = 2";
            $sukses2 = mysqli_query($conn, $sql);
            if ($sukses2){
                header("refresh:0.5;url=index.php");
            }
        }

        if ($delete ==='sector3') {
            $sql = "DELETE FROM dht11 WHERE sector = 3";
            $sukses3 = mysqli_query($conn, $sql);
            if ($sukses3){
                header("refresh:0.5;url=index.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Monitoring Waktu Panen Buah Pisang</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/custom.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php require 'topnavigation.php'; ?>   
        <div id="layoutSidenav">       
            <?php require 'sidenavigation.php'; ?>
            <div id="layoutSidenav_content">

        <h1>Monitoring Real-Time</h1>
        <div class="container">
        <div class="panel panel-default">
        <div class="panel-body">
        </div>
        <div class="tabel-sector">
            <div class="sector-1">
                <h3>Sector 1</h3>
                <table
                    border="1" cellpadding="8" cellspacing ="0">
                    <tr>
                        <td>No</td>
                        <td>Waktu</td>
                        <td>Sektor</td>
                    </tr>
                    <?php 
                    $no = 1;
                    $conn = mysqli_connect("localhost", "root", "", "sensor_db");
                    $result = mysqli_query ($conn, "SELECT*FROM dht11 WHERE sector = 1 ORDER BY id DESC");
                    $query = mysqli_query ($conn, "SELECT sector, notif FROM dht11 ORDER BY id DESC"); 
                    $row = mysqli_fetch_assoc($query);
                    $status = (int)$row['notif'];
                    $section = (int)$row['sector'];

                    if ($status === 1 && $section===1){
                        echo "<script>alert('PISANG PADA SECTOR 1 SIAP DIPANEN');</script>";
                        // echo "ON";
                    }
                    else {
                        // echo "OFF";
                    }

                    while ($row=mysqli_fetch_assoc ($result)): ?>
                    <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row["time"]; ?></td>
                    <td><?php echo $row["sector"]; ?></td>
                </tr>
                    <?php endwhile; ?>
                </table>
                <a href="?delete=sector1" onClick="return confirm('RESET LOG SECTOR 1?')"><button type="button" class="delete-button">RESET</button></a>
            </div>

            <div class="sector-2">
                <h3>Sector 2</h3>
                <table
                    border="1" cellpadding="8" cellspacing ="0">
                    <tr>
                        <td>No</td>
                        <td>Waktu</td>
                        <td>Sektor</td>
                    </tr>
                    <?php 
                    $no = 1;
                    $conn = mysqli_connect("localhost", "root", "", "sensor_db");
                    $result = mysqli_query ($conn, "SELECT * FROM dht11 WHERE sector = 2 ORDER BY id DESC");
                    $query = mysqli_query ($conn, "SELECT sector, notif FROM dht11 ORDER BY id DESC");  
                    $row = mysqli_fetch_assoc($query);
                    $status = (int)$row['notif'];
                    $section = (int)$row['sector'];

                    if ($status === 1 && $section === 2){
                        echo "<script>alert('PISANG PADA SECTOR 2 SIAP DIPANEN');</script>";
                        // echo "ON";
                    }
                    else {
                        // echo "OFF";
                    }

                    while ($row=mysqli_fetch_assoc ($result)): ?>
                    <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row["time"]; ?></td>
                    <td><?php echo $row["sector"]; ?></td>
                </tr>
                    <?php endwhile; ?>
                </table>
                <a href="?delete=sector2" onClick="return confirm('RESET LOG SECTOR 2?')"><button type="button" class="delete-button">RESET</button></a>
            </div>

            <div class="sector-3">
                <h3>Sector 3</h3>
                <table
                    border="1" cellpadding="8" cellspacing ="0">
                    <tr>
                        <td>No</td>
                        <td>Waktu</td>
                        <td>Sektor</td>
                    </tr>
                    <?php 
                    $no = 1;
                    $conn = mysqli_connect("localhost", "root", "", "sensor_db");
                    $result = mysqli_query ($conn, "SELECT*FROM dht11 WHERE sector = 3 ORDER BY id DESC");
                    $query = mysqli_query ($conn, "SELECT sector, notif FROM dht11 ORDER BY id DESC");  
                    $row = mysqli_fetch_assoc($query);
                    $status = (int)$row['notif'];
                    $section = (int)$row['sector'];

                    if ($status === 1 && $section === 3){
                        echo "<script>alert('PISANG PADA SECTOR 3 SIAP DIPANEN');</script>";
                        // echo "ON";
                    }
                    else {
                        // echo "OFF";
                    }

                    while ($row=mysqli_fetch_assoc ($result)): ?>
                    <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row["time"]; ?></td>
                    <td><?php echo $row["sector"]; ?></td>
                </tr>
                    <?php endwhile; ?>
                </table>
                <a href="?delete=sector3" onClick="return confirm('RESET LOG SECTOR 3?')"><button type="button" class="delete-button">RESET</button></a>
            </div>
            <div class="button-control">
                <h3>control buzzer</h3>
                <?php 
                    if ($statusBuzzer === 1):
                ?>
                <p>Status Buzzer: ON</p>
                <a href="?action=off" type="button" class="buttons">Matikan Buzzer</a>
                <?php 
                    else:
                ?>
                <p>Status Buzzer: OFF</p>
                <a href="?action=on" type="button" class="buttons">Nyalakan Buzzer</a>
                <?php endif; ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
