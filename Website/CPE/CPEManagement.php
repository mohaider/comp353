<?php 
	if (!isset($_SESSION)){
	session_start();
        }
        echo $_SESSION['role'];
        if(!isset($_SESSION['role']))
{
    header('../login.php');
    die();
}
        if ($_SESSION['role'] != "CPE") {
        header('Location:'.$_SESSION['role'].'PHP');
      
    }

	if (isset($_POST['newManager'])){
            header('Location:../addemployee.php');

	}
        if (isset($_POST['existingManager'])){
            header('Location:cpeExistingEmployeeMenu.php');
	}
        
        		echo "<table><tr>";
			echo "<td><a href=\"../CPE.php\">CPE</a></td>";
			echo "<td><a href=\"../Manager.php\">Manager</a></td>";
			echo "<td><a href=\"../Employee.php\">Employee</a>";
		echo "</tr></table>";
        ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>CPE Management Page</title>
    </head>
    <body>
<html>
	<head>
		<title>CPE Management Page</title>
	</head>
	<body>
            <p>
                You are logged in as the CPE. Please select the following functions
            </p>


            
            
            
            
	<form method="POST" action=''>

        <input type="submit" name="newManager" value="Add New Employee/Manager">
        <input type="submit" name="existingManager" value=" Modify existing management(or employees) "> 
	</form>			
	</body>


</html>