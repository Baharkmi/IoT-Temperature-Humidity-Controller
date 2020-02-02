<?php
	//Creates new record as per request
    //Connect to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "iot";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }

    //Get current date and time
    date_default_timezone_set('Asia/Tehran');
    $date = date("Y-m-d");
    $time = date("H:i:s");

    if(!empty($_POST['temp']) && !empty($_POST['humid']))
    {
    	$temp = $_POST['temp'];
    	$humid = $_POST['humid'];

	    $sql = "INSERT INTO logs (temp, humid, Date, Time)
		
		VALUES ('".$temp."', '".$humid."', '".$date."', '".$time."')";

		if ($conn->query($sql) === TRUE) {
		    echo "OK";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}


	$conn->close();
?>