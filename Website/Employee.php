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
		// Redirects to Child Info Page
	}
	if (isset($_POST["StaffInfo"])){
		// Redirects to Staff Info Page
	}
	if (isset($_POST["FamilyInfo"])){
		header('Location: FamilyInfo.php');
	}


}
 ?>
<html>
	<head>
		<title>Emploee Home Page</title>
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
