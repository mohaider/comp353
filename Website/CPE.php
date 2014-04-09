<?php 
	if (!isset($_SESSION)){
	session_start();
        }
        if(!isset($_SESSION['role']))
{
    header('login.php');
    die();
}
        if ($_SESSION['role'] != "CPE") {
        header('Location:'.$_SESSION['role'].'PHP');

    }

	if (isset($_POST['Managerfunc'])){
            header('Location:CPE/CPEManagement.php');
 
	}
	if (isset($_POST['FacilityManagement'])){
            header('Location:CPE/facilityManagement.php');
  
	}
	if (isset($_POST['ChildFacRelo'])){
            header('Location:CPE/childFacilityRelocationMenu.php');
            
	}
        
	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
		
		if ( $access == "Manager" ){
			header('Location: Manager.php');
		}
		if ( $access == "Employee" ){
			header('Location: Employee.php');
		}
	}
 ?>
<html>
	<head>
		<title>CPE Home Page</title>
	</head>
	<body>


<?php include("accesslevel.php"); ?>
	<form method="POST" action=''>
	<input type="submit" name="Managerfunc" value="Manager Function">
	<input type="submit" name="FacilityManagement" value="Facility Management">
	<input type="submit" name="ChildFacRelo" value="Child Facility Relocation">
	</form>	
            
	</body>


</html>
