<?php
	include_once('scripts/db_script.php');
	
	function searchFamily($connection, $lastName)
	{
		$resultSearch = mysqli_query($connection, "SELECT COUNT(*) FROM Family WHERE LastName = '" . $lastName . "';");
		$arr = mysqli_fetch_row($resultSearch);
		$nbFamily = $arr[0];
		return intval($nbFamily);
	}
	
	function getAllFamilyInfo($connection, $lastName)
	{
		$resultFamily = mysqli_query($connection, "SELECT * FROM Family WHERE LastName = '" . $lastName . "';");
		$familyArr = array();
		while ($row = mysqli_fetch_array($resultFamily))
		{
			$family = array($row["ID"], $row["LastName"], $row["PhoneNum"]);
			array_push($familyArr, $family);
		}
		cleanDatabaseBuffer($connection);
		return $familyArr;
	}
	
	function addNewFamily($connection, $lastName, $phoneNumber)
	{
		$resultInsert = mysqli_query($connection, "INSERT INTO Family (LastName, PhoneNum) VALUES ('" . $lastName . "', '" . $phoneNumber . "');");
		$resultNewID = mysqli_query($connection, "SELECT ID FROM Family WHERE LastName = '" . $lastName . "' AND PhoneNum = '" . $phoneNumber . "';");
		if (!$resultInsert)
		{ die ("Fatal Error on insert new family"); }
		else 
		{ 
			$arr = mysqli_fetch_row($resultNewID);
			$ID = $arr[0]; 
			return intval($ID);
		}
	}
	
	function addNewChild($connection, $medicNum, $sex, $dob, $name, $ageGroup)
	{
		$resultInsert = mysqli_query($connection, "INSERT INTO Child (MedicareNum, SEX, DOB, Name, AgeGroup) VALUES ('" . $medicNum . "', '" . $sex . "', '" . $dob . "', '" . $name . "', '" . $ageGroup . "');");
		
		//echo "INSERT INTO Child (MedicareNum, SEX, DOB, Name, AgeGroup) VALUES ('" . $medicNum . "', '" . $sex . "', '" . $name . "', '" . //$ageGroup . "');";
	}
?>