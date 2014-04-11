<?php
	session_start();
	$lastName = $_SESSION['newFamilyLastName'];
	include_once('scripts/family_script.php');
	include_once('scripts/db_script.php');
	$connection = db_connect();
	$family = getAllFamilyInfo($connection, $lastName);
	
	if (isset($_POST["submitWithID"]))
	{
		if ($_POST["submitWithID"] === "Use this ID")
		{
			$_SESSION['newFamilyID'] = $_POST["famID"];
			header('location: addChild.php');
		}
	}
	
	if (isset($_POST["submitWithNoID"]))
	{
		if ($_POST["submitWithNoID"] === "Register")
		{
			header('location: registerfamily.php');
		}
	}
?>
<html>
	<head></head>
	<body>
		List of family in the system with the same last name:
		<?php
			echo "<table>";
			echo "<tr>";
			echo "<th>ID</th>";
			echo "<th>Last Name</th>";
			echo "<th>Phone Number</th>";
			echo "</tr>";
			echo "<tr>";
			for ($i = 0; $i < count($family); ++$i)
			{
				echo "<td>" . $family[$i][0] . "</td>";
				echo "<td>" . $family[$i][1] . "</td>";
				echo "<td>" . $family[$i][2] . "</td>";
			}
			echo "</tr>";
			echo "</table>";
		?>
		
		<br />
		If the family is in the list above, enter the ID in the field below and click "Use this ID". If the family is not in the system, it has to be registered. Click on the button "Register".
		<form method="POST" name="frmFamilySelect" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		ID: <input type="text" name="famID" />
		<input type="submit" name="submitWithID" value="Use this ID" />
		<br /><input type="submit" name="submitWithNoID" value="Register" />		
		</form>
	</body>
</html>