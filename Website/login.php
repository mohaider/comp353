<?php
session_start();
include_once('scripts/login_script.php');
include_once("scripts/db_script.php");

	if (isset($_POST["submitFrmLogin"]))
	{
		if ($_POST["submitFrmLogin"] === "Submit")
		{	
			$empID = $_POST["txtEmpIDName"];
			$password = $_POST["txtPasswordName"];

			if ( $empID == ""  ){
				$message = "Employee ID is Empty";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			elseif ( $password == "" ) {
				$message = "Password Field is Empty";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			else{
				// fields are not empty
				// Checks Logins and see if it is valid
				//if(login($empID, $password)){		
				// Connection to the database again to get the acccess level of the employee 
				// After Verifiation of EMPID and password

				if (login(intval($empID) , $password)) {	
					// Continue .ipper.encs.concordia.ca"	
					$connection = db_connect();
					
					$query = "SELECT Role FROM Employee WHERE EmpID = " . $empID . ";";
					$result = mysqli_query($connection, $query);
					
					$row = mysqli_fetch_row($result);	
	
					$accesslevel = $row[0];
					$_SESSION['access'] = $accesslevel;
					$_SESSION['empID'] = $empID;
						
					$_SESSION['role'] = $accesslevel;
					if ( $accesslevel == "CPE" ) {
						header('Location: CPE.php');
					}
					elseif( $accesslevel == "Manager") {
						header('Location: Manager.php');
					}
					else{
						header('Location: Employee.php');
					}	
					mysqli_close($connection);
				}
			}
			
		}
	}
?>

<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<form id="frmLogin" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<b>Please enter your login inforrmation:</b>
			<br />
			Employee ID: <input name="txtEmpIDName" type="text" id="txtEmpID" />
			<br />
			Password: <input name="txtPasswordName" type="password" id="txtPassword" />
			<br />
			<input name="submitFrmLogin" type="submit" value="Submit" />
		</form>
	</body>
</html>
