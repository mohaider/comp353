<?php 
	if(!isset($_SESSION)) {
		session_start();
	if(isset($_POST["Registration"])){
		// redirec to Registration Page
	}
	if (isset($_POST["PaymentInfo"])){
		header('Location: Employee\PaymentInfo.php');
	}
	if (isset($_POST["ChildInfo"])){
		header('Location: Employee\ChildInfo.php');
	}
	if (isset($_POST["StaffInfo"])){
		header('Location: ViewSchedule.php');

	}
	if (isset($_POST["FamilyInfo"])){
		header('Location: Employee\FamilyInfo.php');
	}
	if (isset($_POST["Schedule"])){
		header('Location: ViewSchedule.php');
	}


}
 ?>
<html>
	<head>
		<title>Employee Home Page</title>
	</head>
	<body>
            <?php
            echo "Role:". $_SESSION['role'];
            ?>


<?php include("accesslevel.php"); ?>
		<form method="POST" action =''> 
		<input type="submit" name="Registration" value="Register Child">
		<input type="submit" name="PaymentInfo" value="Payment Information">
		<input type="submit" name="FamilyInfo" value="Family Information">
		<input type="submit" name="ChildInfo" value="Child Information">
		<input type="submit" name="StaffInfo" value="Staff Information">


		</form>
			
	</body>


</html>
