<?php
	include_once('scripts/db_script.php');
	
	function addEmployee($empName, $empAddress, $empRole, $empSSN)
	{
		$connection = db_connect();
		
		$today = date("Y-m-d");
		$empID = insertEmployee($connection, $empName, $empAddress, $empRole, $today, $empSSN);
		$password = createPassword($empName, $empID);
		insertLogins($connection, $empID, $password);
		
		db_close($connection);
		return $empID;
	}
	
	function insertEmployee($connection, $empName, $empAddress, $empRole, $today, $empSSN)
	{
		$result = mysqli_query($connection, "CALL insertEmployee('" . $empName . "', '" . $empAddress . "', '" 
		. $empRole . "', '" . $today . "', " . $empSSN . ");");
		if ($result === FALSE)
		{
			die (mysqli_error($connection));
		}
		$arr = mysqli_fetch_row($result);
		$empID = $arr[0];
		cleanDatabaseBuffer($connection);
		return $empID;
	}
	
	function insertLogins($connection, $empID, $hashPassword)
	{
		$result = mysqli_query($connection, "INSERT INTO Logins (EmpID, Password) VALUES (" . $empID . ", '" . $hashPassword . "');");
		if ($result === FALSE)
		{
			die (mysqli_error($connection));
		}
	}
	
	function createPassword($empName, $empID)
	{
		$firstChar = substr($empName, 0, 4);
		return md5($firstChar . $empID);
	}
?>