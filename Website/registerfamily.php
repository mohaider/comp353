<?php
	session_start();
	$lastName = $_SESSION['newFamilyLastName'];
	include_once('scripts/family_script.php');
	include_once('scripts/db_script.php');
	if (isset($_POST["submitFrmNewFamily"]))
	{
		if ($_POST["submitFrmNewFamily"] === "Submit")
		{
			$connection = db_connect();
			$phoneNum = $_POST['phoneNum'];
			$_SESSION['newFamilyID'] = addNewFamily($connection, $lastName, $phoneNum);
			db_close($connection);
			header('location: addChild.php');
		}
	}
?>

<html>
	<head>
		<title></title>
	</head>
	<body>
		The family name <?php echo $lastName ?> was not found in our system. If you want to add this family into the system, please fill the information below. If the family is already in the system, go back and make a new search.
		<form name="frmNewFamily" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			Last Name: <?php echo $lastName ?> <br />
			Phone Number (xxx xxx-xxxx): <input type="text" name="phoneNum" /> <br />
			<input type="submit" value="Submit" name="submitFrmNewFamily" />
		</form>
	</body>
</html>