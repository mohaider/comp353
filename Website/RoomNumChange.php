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

	if ( isset($_POST['RoomChange'])){
		$ID = $_POST['EmpID'];
		$RoomNum = $_POST['RoomNum'];
	
		$connection = db_connect();
		$query = "UPDATE Supervises\n"
			."SET RoomNum = '$RoomNum'\n"
			."WHERE EmpID = '$ID';";
		$result = mysqli_query($connection, $query);
		if ( $result ) {
			echo "Room succesfuly Changed";
		}
		else {
			printf("Errormessage: %s\n" , mysqli_error($connection));
		}

	}





 ?>
<html>
	<head>
		<title>Room Number Change</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>
	<table>
	<tr>
	<td>Employee ID</td>
	<td>
<?php
	// Verify Query Statement
      	 $connection = db_connect();
         $query = "SELECT EmpID FROM EmployeeLists ".$cond;

         $result = mysqli_query($connection,$query);
         if (!$result)
         {
		print_r(mysqli_error($con));
         }
         echo "<select name=\"EmpID\">";
         while ( $row = mysqli_fetch_array($result, MYSQL_BOTH)) {
         	echo "<option value=\"".$row['EmpID']."\">" .$row['EmpID']. "</option>";
	}
        echo "</select>";
?>
	</td>
	</tr>
	<tr>	
	<td>Room Number</td>
	<td>
<?php
	$query2 = "SELECT RoomNum FROM Room";
	
	$result2 = mysqli_query($connection, $query2);
	if (!$result2){
		print_r(mysqli_error($connection));
	}
	else{
		echo "<select name=\"RoomNum\">";
		while ( $row = mysqli_fetch_array($result2 , MYSQL_BOTH)){
			echo "<option value=\"".$row['RoomNum']."\">" .$row['RoomNum']."</option>";
		}
		echo "</select>";
	}
	db_close($connection);
?>
	</td>
	
	</table>
	<input type="Submit" name="RoomChange" value="Submit"/>	
	</form>
				
	</body>
<html>


