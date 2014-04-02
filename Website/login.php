<?php
session_start();

	//include_once('scripts/login_script.php');
	if (isset($_POST["submitFrmLogin"]))
	{
		if ($_POST["submitFrmLogin"] === "Submit")
		{	
			$empID = intval($_POST["txtEmpIDName"]);
			$password = $_POST["txtPasswordName"];
			//if(login($empID, $password))
			//{
				// Connection to the database again to get the acccess level of the employee
				/*
				$connection = mysql_connect("localhost", "root", "");
				if (!$connection)
					die ("Could not establish connection" . mysql_error());
				
				if (!mysql_select_db("Daycare"))
					die ("Could not connect to database" . mysql_error());
				$query = "SELECT Role FROM Employee WHERE EmpID = " . $empID . ";";
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
				
				$accesslevel = $row['Role'];
				$_SESSION['access'] = $accesslevel;
				*/
				// for testing purposes
				// for now the redirecting of it is the Full Location
				
				$accesslevel = $password;
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
				
				
				
				//mysql_close($connection);
				/*header('Location: roomInfo.php');   */
			//}
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
