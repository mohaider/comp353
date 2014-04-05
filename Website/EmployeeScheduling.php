<?php
	include_once("scripts/db_script.php");
	if (!isset($_SESSION)){
		session_start();
	}
	if ( isset($_SESSION['role']) ) {
	$access = $_SESSION['role'];
		if ( $access == "Employee" ){
			header('Location: Employee.php');
		}
	}
	

	if ( isset($_POST['ViewSchedule'])){
		header ('Location: ViewSchedule.php');
	}

	if ( isset($_POST['EditSchedule'])){
		header ('Location: EditSchedule.php');
	}



 ?>
<html>
	<head>
		<title>Employee Scheduling</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>
	<input type="Submit" name="ViewSchedule" value="View Schedule"/>
	<input type="Submit" name="EditSchedule" value="Edit Schedule"/>	
	</form>
				
	</body>
<html>


