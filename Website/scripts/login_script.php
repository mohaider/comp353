<?php
	//Login scripts
	
	function login($empID, $password)
	{	
		//connect to DBMS
		$connection = mysql_connect("localhost", "root", "");
		if (!$connection)
			die ("Could not establish connection" . mysql_error());
			
		//select DB
		if (!mysql_select_db("Daycare"))
			die ("Could not connect to database" . mysql_error());

		//Validate ID
		if (validateEmpID($connection, $empID))
		{
			$userInfo = getUserInfo($connection, $empID);		
			return (validateInfo($empID, $password, $userInfo[0], $userInfo[1])); 
		}
		else
		{ return false; }
		
		mysql_close($connection);
	}
	
	function validateEmpID($connection, $empID)
	{
		//we hae to verify that the employee ID is in the database
		$result = mysql_query("SELECT COUNT(*) FROM Logins WHERE EmpID = " . $empID . ";");
		
		//if we have 0 or more than 1 result for a specific EmpID, there is a problem, so only
		//accept when we have 1 user.
		return (mysql_result($result, 0) == 1);
	}
	
	function getUserInfo($connection, $empID)
	{
		//get user password from database using the employee id provided
		//return cryped password (received from database)
		
		//I think returning an array in the form [empID, cryptedPassword] would be good
		//because we should only received one row since IDs should be unique
		$result = mysql_query("SELECT * FROM Logins WHERE EmpID = " . $empID . ";");
		$row = mysql_fetch_row($result);
		$userInfo = array($row[0], $row[1]);
		return $userInfo;
	}
	
	function validateInfo($empID, $password, $dataBaseEmpID, $dataBasePassword)
	{
		//verify that both the user input and the actual password match by comparing their hash
		$cryptedPassword = encryptPassword($password);
		return ($cryptedPassword == $dataBasePassword);
	}
	
	function encryptPassword($password)
	{
		return md5($password);
	}
?>