<?php
	//Create Data base if not exists
	$servername = "localhost";
	$username = "root";
	$password = "";

	// Create connection
	$conn = new mysqli($servername, $username, $password);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	// Create database
	$sql = "CREATE DATABASE iot";
	if ($conn->query($sql) === TRUE) {
	    echo "Database created successfully";
	} else {
	    //echo "Error creating database: " . $conn->error;
	}

	$conn->close();

	echo "<br>";
	//Connect to database and create table
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "iot";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	// sql to create table
	$sql = "CREATE TABLE logs (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	temp VARCHAR(30),
	humid VARCHAR(30),
	`Date` DATE NULL,
	`Time` TIME NULL, 
	`TimeStamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Table logs created successfully";
	} else {
	    //echo "Error creating table: " . $conn->error;
	}
	
	// sql to create table
	$sql2 = "CREATE TABLE control (
	id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	htemp INT(7),
	ltemp INT(7),
	hhumid INT(7),
	lhumid INT(7)
	)";

	if ($conn->query($sql2) === TRUE) {
	    echo "Table control created successfully";
	} else {
	    //echo "Error creating table: " . $conn->error;
	}

	$conn->close();
?>