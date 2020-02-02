<?php

	session_start();
	include('DataBase.php');

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
			
	if (isset($_POST['submit'])) {
		if(!empty($_POST['htemp']) && !empty($_POST['ltemp']) && !empty($_POST['hhumid']) && !empty($_POST['lhumid'])){
			$sql = "SELECT COUNT(*) AS count FROM control;";
			$result=mysqli_query($conn,$sql);
			$num_rows = mysqli_fetch_assoc($result);
			$count = $num_rows['count'];
			if($count != 0){
				$sql = "DELETE FROM control";
				if ($conn->query($sql) === TRUE) {
					//echo "Deleted control rows!";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
			
			$htemp = $_POST['htemp'];
			$ltemp = $_POST['ltemp'];
			$hhumid = $_POST['hhumid'];
			$lhumid = $_POST['lhumid'];
			
			$sql = "INSERT INTO control (htemp, ltemp, hhumid, lhumid)
		
			VALUES ('".$htemp."', '".$ltemp."', '".$hhumid."', '".$lhumid."')";

			if ($conn->query($sql) === TRUE) {
				//echo "Inserted into control!";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}else{
			// This is in the PHP file and sends a Javascript alert to the client
			$message = "Fill all the information!";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}
		header('Location: iot.php');
	}
	
	if (isset($_POST['back'])) {
		header('Location: iot.php');
	}
	
	$sql = "SELECT COUNT(*) AS count FROM control;";
	$result=mysqli_query($conn,$sql);
    $num_rows = mysqli_fetch_assoc($result);
    $count = $num_rows['count'];
	if($count != 0){	
		$sql = "SELECT * FROM control ORDER BY id DESC LIMIT 1";
		$result=mysqli_query($conn,$sql);
		$info=mysqli_fetch_row($result);
	}
	
	
?>
<!DOCTYPE html> 
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
  <title>IOT Weather Report</title>
  <style>
	   html { font-family: 'Open Sans', sans-serif; display: block; margin: 0px auto; text-align: center;color: #333333;}
	   h1 {margin: 30px auto 30px; color:#1a7867; font-size:30px; font-weight: 20000; margin-bottom : 50px;}
	   .btn {
			padding: 10px;
			padding-left: 20px;
			padding-right: 20px;
			margin-top: 60px;
			font-size: 17px;
			color: white;
			background: #1a7867;
			border: none;
			border-radius: 5px;
			font-weight: 500;
		}
	  .side-by-side{display: inline;vertical-align: middle;position: relative;  padding: 7px;}
	  .humidity-icon{background-color: #3498db;width: 30px;height: 30px;border-radius: 50%;line-height: 36px;}
	  .humidity-text{font-weight: 600;padding-left: 15px;font-size: 19px;width: 160px;text-align: left;}
	  .humidity{font-weight: 300;font-size: 60px;color: #3498db;}
	  .temperature-icon{background-color: #f39c12;width: 30px;height: 30px;border-radius: 50%;line-height: 40px;}
	  .temperature-text{font-weight: 600;padding-left: 15px;font-size: 19px;width: 160px;text-align: left;}
	  .temperature{font-weight: 300;font-size: 60px;color: #f39c12;}
	  .superscript{font-size: 17px;font-weight: 600;position: absolute;right: -20px;top: 15px;}
	  .data{padding: 10px;}
	  .textbox{display: inline-table;}
	</style>
 </head>
 
 <body>
 <div id="webpage">
  <h1>Temperature & Humidity Control</h1>
   <form method="post" action="control.php">
  <div class="data">
	<table class="side-by-side" style="width:50%">
	  <tr>
		<th>  <div class="side-by-side temperature-icon">
	  <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	  width="9.915px" height="22px" viewBox="0 0 9.915 22" enable-background="new 0 0 9.915 22" xml:space="preserve">
	  <path fill="#FFFFFF" d="M3.498,0.53c0.377-0.331,0.877-0.501,1.374-0.527C5.697-0.04,6.522,0.421,6.924,1.142
	  c0.237,0.399,0.315,0.871,0.311,1.33C7.229,5.856,7.245,9.24,7.227,12.625c1.019,0.539,1.855,1.424,2.301,2.491
	  c0.491,1.163,0.518,2.514,0.062,3.693c-0.414,1.102-1.24,2.038-2.276,2.594c-1.056,0.583-2.331,0.743-3.501,0.463
	  c-1.417-0.323-2.659-1.314-3.3-2.617C0.014,18.26-0.115,17.104,0.1,16.022c0.296-1.443,1.274-2.717,2.58-3.394
	  c0.013-3.44,0-6.881,0.007-10.322C2.674,1.634,2.974,0.955,3.498,0.53z"/>
	  </svg> </div></th>
		<th>  <div class="side-by-side temperature-text">High Temperature Limit </div></th>
		<th>  <div class="side-by-side textbox">	
		<input size="10" type="text" name="htemp" value="<?php if(!empty($info[1])){echo $info[1];} ?>" >  </div></th>
	</tr>
	
	<tr>
		<th>  <div class="side-by-side temperature-icon">
	  <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	  width="9.915px" height="22px" viewBox="0 0 9.915 22" enable-background="new 0 0 9.915 22" xml:space="preserve">
	  <path fill="#FFFFFF" d="M3.498,0.53c0.377-0.331,0.877-0.501,1.374-0.527C5.697-0.04,6.522,0.421,6.924,1.142
	  c0.237,0.399,0.315,0.871,0.311,1.33C7.229,5.856,7.245,9.24,7.227,12.625c1.019,0.539,1.855,1.424,2.301,2.491
	  c0.491,1.163,0.518,2.514,0.062,3.693c-0.414,1.102-1.24,2.038-2.276,2.594c-1.056,0.583-2.331,0.743-3.501,0.463
	  c-1.417-0.323-2.659-1.314-3.3-2.617C0.014,18.26-0.115,17.104,0.1,16.022c0.296-1.443,1.274-2.717,2.58-3.394
	  c0.013-3.44,0-6.881,0.007-10.322C2.674,1.634,2.974,0.955,3.498,0.53z"/>
	  </svg> </div></th>
		<th>  <div class="side-by-side temperature-text">Low Temperature Limit </div></th>
		<th>  <div class="side-by-side textbox">	
		<input size="10" type="text" name="ltemp" value="<?php if(!empty($info[2])){echo $info[2];} ?>" > </div></th>
	</tr>
	
  <tr>
    <th><div class="side-by-side humidity-icon">
	  <svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="17.955px" viewBox="0 0 13 17.955" enable-background="new 0 0 13 17.955" xml:space="preserve">
	  <path fill="#FFFFFF" d="M1.819,6.217C3.139,4.064,6.5,0,6.5,0s3.363,4.064,4.681,6.217c1.793,2.926,2.133,5.05,1.571,7.057
	  c-0.438,1.574-2.264,4.681-6.252,4.681c-3.988,0-5.813-3.107-6.252-4.681C-0.313,11.267,0.026,9.143,1.819,6.217"></path>
	  </svg>
	  </div></th>
    <th>  <div class="side-by-side humidity-text">High Humidity Limit</div></th>
    <th>  <div class="side-by-side textbox">	
	<input size="10" type="text" name="hhumid" value="<?php if(!empty($info[3])){ echo $info[3];} ?>"> </div></th>
  </tr>  
  
  <tr>
    <th><div class="side-by-side humidity-icon">
	  <svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="17.955px" viewBox="0 0 13 17.955" enable-background="new 0 0 13 17.955" xml:space="preserve">
	  <path fill="#FFFFFF" d="M1.819,6.217C3.139,4.064,6.5,0,6.5,0s3.363,4.064,4.681,6.217c1.793,2.926,2.133,5.05,1.571,7.057
	  c-0.438,1.574-2.264,4.681-6.252,4.681c-3.988,0-5.813-3.107-6.252-4.681C-0.313,11.267,0.026,9.143,1.819,6.217"></path>
	  </svg>
	  </div></th>
    <th>  <div class="side-by-side humidity-text">Low Humidity Limit</div></th>
    <th>  <div class="side-by-side textbox">	
	<input size="10" type="text" name="lhumid" value="<?php if(!empty($info[4])){ echo $info[4];} ?>"> </div></th>
  </tr> 
	  
</table> 
</div>
	<Button type="submit" class="btn" name="back" >Back</Button>
	<Button type="submit" class="btn" name="submit" >Submit</Button>
</form>


  </body>
 </html>