<?php
	if (!isset($_SESSION)){
		session_start();
	}
	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
		if ( $access == "Employee" ){
			header('Location: Employee.php');
		}
	}

	// Check which button was pressed
	
	if(isset($_POST["Edit"])){
		header('Location: EditEmployee.php');
	}

        if(isset($_POST["Terminate"])){
		header('Location:Terminate.php');
        }

        if(isset($_POST["EditPassword"])){
		header('Location: EditPassword.php');	
        }
	if(isset($_POST["RoomNumChange"])){
		header('Location: RoomNumChange.php');
	}






 ?>
<html>
	<head>
		<title>Existing Emplyee</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>
	<input type="submit" name="Edit" value="Edit Employee Information">
	<input type="submit" name="Terminate" value="Fire an Employee">
	<input type="submit" name="RoomNumChange" value="Room Number Change">
	<input type="submit" name="EditPassword" value="Edit Password">

	</form>
				
	</body>
<html>


