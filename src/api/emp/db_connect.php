<?php
	// Connecting to mysql (phpmyadmin) database<?php
		$hostname = "50.62.209.51:3306"; 
		$user = "lata"; 
		$password = "clpl@123#"; 
		$database = "clpl_emp"; 
		$con = mysqli_connect($hostname, $user, $password) or die("Server error!");
		mysqli_select_db($con,$database) or die("Failed to connect to database");
	

		// $con= mysqli_connect("localhost","root","","clpl_emp")  or die(mysql_error());

		// $hostname = "127.0.0.1"; 
		// $user = "root"; 
		// $password = ""; 
		// $database = "clpl_emp";
		// $con = mysqli_connect($hostname, $user, $password) or die("Server error!");
		// mysqli_select_db($con,$database) or die("Failed to connect to database");
?>
