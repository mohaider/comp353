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
	
	if ( isset($_POST["EditPassword"])) {
		$password = $_POST["Password"];
		$password2 = $_POST["ConfirmPassword"];
		$ID = $_POST["EmpID"];		

		if ( $password == NULL || $password2 == NULL) {
                        $message = "Password fields are empty";
                        echo "<script type='text/javascript'>alert('$message');</script>";
		}
		elseif ( $password != $password2 ){
			$message = "Password does not match";
                	echo "<script type='text/javascript'>alert('$message');</script>";
		}
		else {
		$connection = db_connect();
		$encrypw = md5($password);		
	
		$query = "UPDATE LogIns\n"
			."SET Password = '$encrypw'\n"
			."WHERE EmpID = '$ID';";
		$result = mysqli_query($connection , $query);
		
		if ( $result ){
			echo  "Password Succcessfully Changed for Employee ID " .$ID;
		}
		else {
			printf("Errormessage: %s\n", mysqli_error($connection));
		}





		}
	}
		
?>
<html>
	<head>
		<title>Edit Password</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form name="frmEditPassword" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
			<tr>
				<td>Employee ID</td>
				<td>

				<?php 
					// Get EmpId for dropdownlist

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

					db_close($connection);
				?>	
				</td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input name="Password" id="Password" type="password" /><td>
			</tr>
			<tr>
				<td>Confirm Password</td>
				<td><input name="ConfirmPassword" id="ConfirmPassword" type="password" /> </td>
			</tr>

		<tr>
		<td><input type="submit" name="EditPassword" value="submit" /></td>
		</tr>			
	</form>
				
	</body>


</html>

