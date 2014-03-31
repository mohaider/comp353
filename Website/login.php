<?php
	include_once('scripts/login_script.php');
	if (isset($_POST["submitFrmLogin"]))
	{
		if ($_POST["submitFrmLogin"] === "Submit")
		{	
			$empID = $_POST["txtEmpIDName"];
			$password = $_POST["txtPasswordName"];
			if(login($empID, $password))
			{
				header('Location: roomInfo.php');
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