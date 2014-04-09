<?php
	if (!isset($_SESSION)){
	session_start();
	}
	if(isset($_POST["StaffManagement"])){
		header('Location: StaffManagement.php');
	}
	if(isset($_POST["FacilityManagement"])){
		header('Location: RoomSelect.php');
	}
	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
		
		if ( $access == "Employee" ){
			header('Location: Employee.php');
		}
	}


 ?>
<html>
	<head>
		<title>Manager Home Page</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>
	<input type="submit" name="StaffManagement" value="Staffing Management">
	<input type="submit" name="FacilityManagement" value="Facility Management">

	</form>
				
	</body>


</html>
