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
		return $group;
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
	
	function getMaxNumberChildren($connection, $roomNumber, $nbStaff, $group)
	{
		$resultType= mysqli_query($connection, "SELECT Type FROM Facility f JOIN Houses h on f.ID = h.FacilityID WHERE h.RoomNum = '" . $roomNumber . "';");
		$row = mysqli_fetch_row($resultType);
		$type = $row[0];
		if ($type === "Home")
		{
			if ($group === "Infant")
			{
				$resultAgeGroup = mysqli_query($connection, "SELECT COUNT(*) FROM Room r JOIN Houses h ON r.RoomNum = h.RoomNum JOIN Facility f ON h.FacilityID = f.ID WHERE r.AgeGroup='Toddler' AND f.ID = (SELECT FacilityID FROM Houses WHERE RoomNum = '" . $roomNumber . "');");
				$row = mysqli_fetch_row($resultAgeGroup);
				$nb = intval($row[0]);
				if ($nb === 1)
				{
					$resultAgeGroup = mysqli_query($connection, "SELECT r.RoomNum FROM Room r JOIN Houses h ON r.RoomNum = h.RoomNum JOIN Facility f ON h.FacilityID = f.ID WHERE r.AgeGroup='Toddler' AND f.ID = (SELECT FacilityID FROM Houses WHERE RoomNum = '" . $roomNumber . "');");
					$row = mysqli_fetch_row($resultAgeGroup);
					$otherID = $row[0];
					echo $otherID;
					$resultNumber = mysqli_query($connection, "SELECT COUNT(*) FROM Child c JOIN SeatedInto s ON c.MedicareNum = s.MedicareNum WHERE s.RoomNum = '" . $otherID . "';");
					$arr = mysqli_fetch_row($resultNumber);
					if (9-intval($arr[0]) > 4)
					{
						return 4;
					}
					else
					{
						return 9-intval($arr[0]);
					}
				}
				else
				{ return 9; }
			}
			else
			{
				$resultAgeGroup = mysqli_query($connection, "SELECT COUNT(*) FROM Room r JOIN Houses h ON r.RoomNum = h.RoomNum JOIN Facility f ON h.FacilityID = f.ID WHERE r.AgeGroup='Infant' AND f.ID = (SELECT FacilityID FROM Houses WHERE RoomNum = '" . $roomNumber . "');");
				$row = mysqli_fetch_row($resultAgeGroup);
				$nb = intval($row[0]);
				echo $nb;
				if ($nb === 1)
				{
					$resultAgeGroup = mysqli_query($connection, "SELECT r.RoomNum FROM Room r JOIN Houses h ON r.RoomNum = h.RoomNum JOIN Facility f ON h.FacilityID = f.ID WHERE r.AgeGroup='Infant' AND f.ID = (SELECT FacilityID FROM Houses WHERE RoomNum = '" . $roomNumber . "');");
					$row = mysqli_fetch_row($resultAgeGroup);
					$otherID_ = $row[0];
					echo $otherID_;
					$resultNumber = mysqli_query($connection, "SELECT COUNT(*) FROM Child c JOIN SeatedInto s ON c.MedicareNum = s.MedicareNum WHERE s.RoomNum = '" . $otherID_ . "';");
					$arr = mysqli_fetch_row($resultNumber);
					return 9-intval($arr[0]);
				}
				else
				{ return 9; }
			}
	 	}
		else
		{
			if ($group === "Infants")
			{
				return 5*$nbStaff;
			}
			else
			{
				return 8*$nbStaff;
			}
		}
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