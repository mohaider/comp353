<?php
	if (!isset($_SESSION)){
		session_start();
	}

	if (isset($_POST["NewEmployee"])){
		// New Employyee Page
	}
	
	if(isset($_POST["ExistingEmployee"])){
		header('Location: ExistingEmployee.php');
	}	
	if(isset($_POST["Scheduling"])){
		header('Location: EmployeeScheduling.php');
	}
	


	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
		
	}

	if ( $access == "Employee"){
		header('Location: Employee.php');
	}

?>

<html>
	<head>
		<title>Staff Management</title>
	</head>
<body>
<?php include("accesslevel.php"); ?>
	<form method="POST" action =''>
		<input type="submit" name="NewEmployee"	value="New Employee">
		<input type="submit" name="ExistingEmployee" value="Existing Emploee">
		<input type ="submit" name="Scheduling" value="Scheduling Employee Information">
	</form>
</body>

</html>
