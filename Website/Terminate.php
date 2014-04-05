<?php
if (!isset($_SESSION)){
	session_start();
	}
	include_once("scripts/db_script.php");
	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
		
		if ( $access == "Employee" ){
			header('Location: Employee.php');
		}
	}
	$connection = db_connect();
	// Get the FacilityID for The dropdown list
	// $cond is the extra condition statement used in the dropdown list
	// hide the 
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
					
					$cond = "WHERE FacilityID = '$FacID' ";
					
					// Hide the CPE in the list
					$newquery = "SELECT EmpID From Employee WHERE Role = 'CPE';";
					$result = mysqli_query($connection , $newquery);
					
					// put the result into an array
					while ( $row = mysqli_fetch_array($result , MYSQL_BOTH)){
						$rows[] = $row;
					}
					// Append the Statement 
					foreach($rows as $row){
						$id = $row['EmpID'];
						$cond .= "AND EmpID != '$id' ";
					}
					$cond .= ";";
					
				}
		}
		mysqli_close($connection);
	}
	
	
	
	if ( isset($_POST["TerminateEmployee"])) {
		$connection = db_connect();		
		$ID = $_POST['EmpID'];
		
		// Delete The Employee From The Employee Table
		$query2 = "UPDATE Employee "
			."SET Role='NLE'"
			."WHERE EmpID ='$ID';";
		$result = mysqli_query($connection, $query2);
		if ( $result ) 
		{	
			echo "Employee successful fired";
		}
		else {
			printf("ErrorMessage: %s\n", msqli_error($connection));
		}
		mysqli_close($connection);
	}



?>
<html>
	<head>
		<title>Terminate</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form name="fromTerminateEmployee" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
			<tr>
				<td>Employee ID</td>
				<td>

				<?php 
					// Connecting to DB to get ALL EMPID

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
		<td><input type="submit" name="TerminateEmployee" value="submit" /></td>
		</tr>			
	</form>
				
	</body>


</html>

