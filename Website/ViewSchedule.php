<?php
	include_once("scripts/db_script.php");
	if (!isset($_SESSION)){
		session_start();
	}
	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
	}

	if ( isset($_POST['ViewSchedule'])){
		header ('Location: ViewSchedule.php');
	}

	if ( isset($_POST['EditSchedule'])){
		header ('Location: EditSchedule.php');
	}
	
	
	$connection = db_connect();
	// Get the FacilityID for The dropdown list
	// $cond is the extra condition statement used in the dropdown list
	$cond = ";";
	if ( isset($_SESSION['role'])) {
		$access = $_SESSION['role'];
		$id = $_SESSION['EmpID'];
		if ( $access == "Manager") {
			$query = "SELECT FacilityID FROM EmployeeLists WHERE EmpID = '$id';";
			$result = mysqli_query($connection , $query);
		
				if ( $result ) {
					$row = mysqli_fetch_array($result, MYSQL_BOTH);
					$FacID = $row['FacilityID'];
					$cond = "Where FacilityID = '$FacID'";
				}
				

		}
		mysqli_close($connection);
	}

 ?>
<html>
	<head>
		<title>View Schedule</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>
<?php if ( $access != "Employee" ) {
	echo "<input type=\"Submit\" name=\"ViewSchedule\" value=\"View Schedule\"/>";
	echo "<input type=\"Submit\" name=\"EditSchedule\" value=\"Edit Schedule\"/>";
	}
?>
	</form>
	
	<form name="formViewSchedule" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<table>
	<tr>
	<th>Employee ID</th>
	<th>Days</th>
		<th>Hours</th>
	</tr>
<?php
	$connection = db_connect();
	if ( $access == "CPE" ) {
		$cond = "";
	}
	elseif ($access == "Employee") {
		$ID = $_SESSION['EmpID'];
		$cond = "Where EmpId = $ID";
	}
	$query = "SELECT * FROM Schedule Join Employee On Schedule.EmpID = Employee.EmpID WHERE Schedule.EmpID IN ( Select EmpId From EmployeeLists ".$cond.");";
	
	$result = mysqli_query($connection , $query );
	if ( $result ) {
		while ( $row = mysqli_fetch_array($result , MYSQL_BOTH )) {
			$rows[] = $row;
		}
	
		foreach ( $rows as $row ) {
			echo "<tr>";
			echo "<td>".$row['EmpID']."</td>";
			echo "<td>".$row['Day']."</td>";
			echo "<td>".$row['Hours']."</td>";
			echo "</tr>";
			}
	}
	else {
		print_r(mysqli_error($connection));
	}

?>
	</table>
	</form>
				
	</body>
<html>


