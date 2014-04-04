<?php 
	if(!isset($_SESSION)) {
		session_start();
	if(isset($_POST["Registration"])){
		// redirec to Registration Page
	}
	if (isset($_POST["PaymentInfo"])){
		// Redirect to Payment Info Page
	}
	if (isset($_POST["ChildInfo"])){
		header('Location: Employee\ChildInfo.php');
	}
	if (isset($_POST["StaffInfo"])){
		// Redirects to Staff Info Page
	}
	if (isset($_POST["FamilyInfo"])){
		header('Location: Employee\FamilyInfo.php');
	}


}
 ?>
<html>
	<head>
		<title>Employee Home Page</title>
	</head>
	<body>


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
