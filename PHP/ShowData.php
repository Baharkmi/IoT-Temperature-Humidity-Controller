<!DOCTYPE html>
<html>
<head>
 <title>IOT Weather Report</title>
<meta http-equiv="refresh" content="5">
</head>
<body>
<div class="data">
<form method="post" action="showdata.php">
	<Button type="submit" class="btn" name="iot" >Home</Button>
</form>
<h1>IOT Temperature & Humidity Log</h1>
</div>

 <?php
 	if (isset($_POST['iot'])) {
		header('Location: iot.php');
	}
 ?>
<style>

html { font-family: 'Open Sans', sans-serif; display: block; margin: 0px auto; text-align: center;color: #333333;}

h1 {margin: 30px auto 30px; color:#1a7867; font-size:30px; font-weight: 510;}

body{padding-left: 100px;
	padding-right: 100px;
	display: inline-block;
}


.btn {
	padding: 5px;
	padding-left: 20px;
	padding-right: 20px;
	margin-top: 5px;
	font-size: 17px;
	color: white;
	background: #1a7867;
	border: none;
	border-radius: 5px;
	font-weight: 500;
	float : left;
}
#table {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#table td, #table th {
    border: 1px solid #ddd;
    padding: 30px;
}

#table tr:nth-child(even){background-color: #f2f2f2;}

#table tr:hover {background-color: #ddd;}

#table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: center;
    background-color: #1a7867;
    color: white;
}
</style>

<?php
	include('DataBase.php');
	include('Server.php');
	
    //Connect to database and create table
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "iot";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
        echo "<a href='database.php'>If first time running click here to install database</a>";
    }
	
?> 

<?php 
    $sql = "SELECT * FROM logs ORDER BY id DESC";
    if ($result=mysqli_query($conn,$sql))
    {
      // Fetch one row
      echo "<TABLE id='table'>";
      echo "<TR></TH><TH>Temperature</TH><TH>Humidity</TH><TH>Time</TH><TH>Date</TH></TR>";
      while ($row=mysqli_fetch_row($result))
      {
        echo "<TR>";
        echo "<TD>".$row[1]."</TD>";
        echo "<TD>".$row[2]."</TD>";
        echo "<TD>".$row[4]."</TD>";
        echo "<TD>".$row[3]."</TD>";
        echo "</TR>";
      }
      echo "</TABLE>";
      // Free result set
      mysqli_free_result($result);
    }

    mysqli_close($conn);
?>
</body>
</html>