<?php
	include_once('scripts/db_script.php');
	
	function getFacilityID($connection, $roomID)
	{
		$resultFadilityID = mysqli_query($connection, "SELECT FacilityID FROM Houses WHERE RoomNum = '" . $roomID . "';");
		return mysqli_fetch_row($resultFadilityID)[0];
	}
	
	function getAgeGroup($connection, $roomID)
	{
		$resultAgeGroup = mysqli_query($connection, "SELECT AgeGroup FROM Room WHERE RoomNum = '" . $roomID . "';");
		$row = mysqli_fetch_row($resultAgeGroup);
		$group = $row[0];
		return intval($group);
	}
	
	function getStaffList($connection, $roomID)
	{
		$resultStaff = mysqli_query($connection, "SELECT e.EmpID, e.Name FROM Employee AS e JOIN Supervises AS s ON e.EmpID = s.EmpID WHERE RoomNum = '" . $roomID . "';");
		$empList = array();
		while ($row = mysqli_fetch_array($resultStaff))
		{
			$emp = array($row["EmpID"], $row["Name"]);
			array_push($empList, $emp);
		}
		
		cleanDatabaseBuffer($connection);
		return $empList;
	}
	
	function getChildrenList($connection, $roomID)
	{
		$resultChildren = mysqli_query($connection, "SELECT c.MedicareNum, c.Name FROM Child AS c JOIN SeatedInto s ON c.MedicareNum = s.MedicareNum WHERE s.RoomNum = '" . $roomID . "';");
		$childrenList = array();
		while ($row = mysqli_fetch_array($resultChildren))
		{
			$child = array($row["MedicareNum"], $row["Name"]);
			array_push($childrenList, $child);
		}
		
		cleanDatabaseBuffer($connection);
		return $childrenList;
	}
	
	function getNumberOfChildren($connection, $roomID)
	{
		$resultNumber = mysqli_query($connection, "SELECT COUNT(*) FROM Child c JOIN SeatedInto s ON c.MedicareNum = s.MedicareNum WHERE s.RoomNum = '" . $roomID . "';");
		$arr = mysqli_fetch_row($resultNumber);
		return intval($arr[0]);
	}
	
	function modifyAgeGroup($connection, $roomID, $ageGroup)
	{
		$resultAgeGroup = mysqli_query($connection, "UPDATE Room SET AgeGroup = '" . $ageGroup . "' WHERE RoomNum = '" . $roomID . "';");
		cleanDatabaseBuffer($connection);
	}
?>