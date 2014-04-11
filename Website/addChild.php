<?php
	session_start();
	include_once('scripts/family_script.php');
	include_once('scripts/db_script.php');
	$familyID = $_SESSION['newFamilyID'];
	if (isset($_POST["submitFrmNewChild"]))
	{
		if ($_POST["submitFrmNewChild"] === "Submit")
		{
			//echo "lol";
			$connection = db_connect();
			$medicNum = $_POST['medicaNum'];
			$childName = $_POST['nameC'];
			$sex = $_POST['sexSelect'];
			$dob = $_POST['dob'];
			$group = $_POST['ageSelect'];
			
			addNewChild($connection, $medicNum, $sex, $dob, $childName, $group, $familyID);
			echo "Child registered in the system. You can add another child to this family or return to the main page. <br />";
		}
	}
?>

<html>
	<head></head>
	<body>
		Fill the information below to register a new child in the system.
		<form name="frmNewChild" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			Family ID: <?php echo $familyID; ?> <br/>
			Medicare Number (XXXX 0000 0000): <input type="text" name="medicaNum" /> <br />
			Name: <input type="text" name="nameC" /> <br />
			Sex: <select name="sexSelect"><option value="Male">Male</option><option value="Female">Female</option></select> <br />
			Date of Birth (yyyy-mm-dd): <input type="text" name="dob" /> <br />
			Age Group: <select name="ageSelect"><option value="Infant">Infant</option><option value="Toddlere">Toddler</option></select> <br />
			<input type="submit" value="Submit" name="submitFrmNewChild" />
		</form>
	</body>
</html>