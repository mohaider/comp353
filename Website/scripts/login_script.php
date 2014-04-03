<?php
	//Login scripts
	
	include_once('scripts/db_script.php');
	function login($empID, $password)
	{	
		//connect to DBMS
		$connection = db_connect();
		
		//Validate ID
		if (validateEmpID($connection, $empID))
		{
			$userInfo = getUserInfo($connection, $empID);		
			return (validateInfo($empID, $password, $userInfo[0], $userInfo[1])); 
		}
		else
		{ return false; }
		
		db_close($connection);
	}
	
	function validateEmpID($connection, $empID)
	{
		//we hae to verify that the employee ID is in the database
		$result = mysqli_query($connection, "SELECT COUNT(*) FROM LogIns WHERE EmpID = " . $empID . ";");
		if ($result === FALSE)
		{
			die (mysqli_error($connecton));
		}
		//if we have 0 or more than 1 result for a specific EmpID, there is a problem, so only
		//accept when we have 1 user.
		$row = mysqli_fetch_row($result);

		if ( $row[0] == 1 ) { 
			return true;
		}
		else{	
			$message = "Incorrect EmpID or Password";
                	echo "<script type='text/javascript'>alert('$message');</script>";
		}
	}
	
	function getUserInfo($connection, $empID)
	{
		//get user password from database using the employee id provided
		//return cryped password (received from database)
		
		//I think returning an array in the form [empID, cryptedPassword] would be good
		//because we should only received one row since IDs should be unique
		$result = mysqlI_query($connection, "SELECT * FROM LogIns WHERE EmpID = " . $empID . ";");
		$row = mysqli_fetch_row($result);
		$userInfo = array($row[0], $row[1]);
		return $userInfo;
	}
	
	function validateInfo($empID, $password, $dataBaseEmpID, $dataBasePassword)
	{
		#decrypt password
		$cryptedPassword = encryptPassword($password);
		if ($cryptedPassword == $dataBasePassword){
			return true;
		}
		else {
			$message = "Incorrect EmpID or Password";
                     	echo "<script type='text/javascript'>alert('$message');</script>";
		}
	}
	
	function encryptPassword($password)
	{
		return md5($password);
	}
?>
