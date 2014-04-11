<?php
	session_start();
	include_once('scripts/family_script.php');
	include_once('scripts/db_script.php');
	if (isset($_POST["submitFrmFamilyLook"]))
	{
		if ($_POST["submitFrmFamilyLook"] === "Submit")
		{
			$lastName = $_POST["familyName"];
			$connection = db_connect();
			if (searchFamily($connection, $lastName) === 0)
			{
				$_SESSION['newFamilyLastName'] = $lastName;
				db_close($connection);
				header('location: registerfamily.php');
			}
			else
			{
				$_SESSION['newFamilyLastName'] = $lastName;
				db_close($connection);
				header('location: validateFamily.php');
			}
		}
	}
?>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<form name="frmFamilyLook" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			Enter the family name (last name) of the children you want to register:
			<br />
			<input name="familyName" />
			<br />
			If the family is already registered in the database, the new child will be added to this family. <br />
			If the family does not exist, it will be created.
			<br />
			<input type="submit" value="Submit" name="submitFrmFamilyLook" />
		</form>
	</body>
</html>