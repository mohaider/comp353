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
	
	if ( isset($_POST["EditFormEmployee"])) {
		$Name = $_POST["Name"];
		$Address = $_POST["Address"];
		$SDate = $_POST["SDate"];
		$EDate = $_POST["EDate"];
		$SSN = $_POST["SSN"];
		$ID = $_POST["EmpID"];
		if ( $Name == "" || $Address == "" || $SDate == ""  || $SSN == "" ){
			$message = "Cant have empty fiels!";
                	echo "<script type='text/javascript'>alert('$message');</script>";
		}
		else{
	
			// Update Employee
			$query = "UPDATE Employee\n"
				. "SET Name = '$Name', Address = '$Address' , StartDate = '$SDate' , EndDate = '$EDate' , SSN = '$SSN'\n"
				. "WHERE EmpID = '$ID';";
			$result = mysqli_query($connection, $query);

			if ( $EDate == "" || $EDate == NULL ){

				// if theres no END Date set the End date field to Null 
				$query1 = "UPDATE Employee\n"
					. "SET EndDate = NULL\n"
					. "WHERE EmpID = '$ID';";
				$result1 = mysqli_query($connection, $query1);
				
			}
			if ( $result ){
				echo "Update Successful";
			}
			else {
				printf("Errormessage: %s\n" , mysqli_error($connection));
			}
			db_close($connection);
		}
	}
?>
<html>
	<head>
		<title>Edit Employee</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form name="frmEditEmployee" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
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
				<td>Employee Name</td>
				<td><input name="Name" id="Name" type="text" /><td>
			</tr>
			<tr>
				<td>Address</td>
				<td><input name="Address" id="Address" type="text"/></td>
			</tr>
			<tr>
				<td>Start Date</td>
				<td><input name="SDate" id="SDate" type="date" /> </td>
			</tr>
			<tr>
				<td>End Date</td>
				<td><input name="EDate" id="EDate" type="Date" /></td>

			</tr>
			<tr>	
				<td>Social Securty Number</td>
				<td><input name="SSN" id="SSN" type="number" /></td>
			</tr>

		<tr>
		<td><input type="submit" name="EditFormEmployee" value="submit" /></td>
		</tr>			
	</form>
				
	</body>


</html>

