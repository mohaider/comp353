<?php 
	if (!isset($_SESSION)){
	session_start();
	}
	if (isset($_POST['Managerfunc'])){
		// Redirect
	}
	if (isset($_POST['FacilityManagement'])){
		// Redirect
	}
	if (isset($_POST['ChildFacRelo'])){
		// Redirect
	}
	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
		
		if ( $access == "Manager" ){
			header('Location: Manager.php');
		}
		if ( $access == "Employee" ){
			header('Location: Employee.php');
		}
	}
 ?>
<html>
	<head>
		<title>CPE Home Page</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>
	<input type="submit" name="Managerfunc" value="Manager Function">
	<input type="submit" name="FacilityManagement" value="Facility Management">
	<input type="submit" name="ChildFacRelo" value="Child Facility Relocation">
	</form>			
	</body>


</html>
