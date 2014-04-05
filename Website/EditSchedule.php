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
	
	
	if ( isset($_POST['Edit'])){
		$connection = db_connect();
		$ID = $_POST['EmpID'];
		$Days = $_POST['Days'];
		$Hours = intval($_POST['Hours']);
		
		$query1 = "Select Count(*) From Schedule Where EmpID = '$ID' AND Day = '$Days';";
		$result = mysqli_query($connection , $query1);
		if ( $result ) {
			$row = mysqli_fetch_array($result , MYSQL_BOTH );
			$count = $row[0];
			
			if ( $count == 0 ) {
					$newquery1 = "INSERT INTO Schedule Values($ID, '$Days', $Hours)";
					$result = mysqli_query($connection, $newquery1);
					
					if ( $result ) {
						echo "Schedule Updated!";
					}
					else {
						printf("Errormessage: %s\n" , mysqli_error($connection));
					}
			}
				// Edit his Schedule
			else {
				$newquery2 = "UPDATE Schedule\n"
							."SET HOURS = $Hours\n"
							."WHERE EmpID = $ID AND Day = '$Days';";
				$result = mysqli_query($connection, $newquery2);
				if ( $result ) {
					echo "Schedule Updated!";
				}
				else {
					printf("Errormessage: %s\n" , mysqli_error($connection));
				}
			}
		}
		
	}
		
	
	

 ?>
<html>
	<head>
		<title>View Schedule</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>

	<input type="Submit" name="ViewSchedule" value="View Schedule"/>
	<input type="Submit" name="EditSchedule" value="Edit Schedule"/>

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
	<tr><td><hr></td><td><hr></td></tr>
	<tr>
	<td>Employee ID</td>
	<td>

	<?php 
		// Get the EmployeeLists

		$connection = db_connect();
		$query = "SELECT EmpID FROM EmployeeLists ".$cond;
		
		$result = mysqli_query($connection,$query);
		if (!$result)
		{
			print_r(mysqli_error($connection));
		}
		echo "<select name=\"EmpID\">";
		while ( $row = mysqli_fetch_array($result, MYSQL_BOTH)) {
			echo "<option value=\"".$row['EmpID']."\">" .$row['EmpID']. "</option>";
		}
		echo "</select>";

		db_close($connection);
	?>	
	</td>
	</tr>

	<tr>
	<td>Days</td>
	<td><select name="Days">
		<option value="Monday">Monday</option>
		<option value="Tuesday">Tuesday</option>
		<option value="Wednesday">Wednesday</option>
		<option value="Thursday">Thursday</option>
		<option value="Friday">Friday</option>
	</td>
	</tr>
	<tr>
		<td>Hours</td>
		<td><input type="number" name="Hours" id="Hours" /></td>
	
	</tr>
	<tr><td><input type="Submit" name="Edit" value="Edit/Add Schedule"/></td></tr>
	</table>
	</form>
				
	</body>
<html>


