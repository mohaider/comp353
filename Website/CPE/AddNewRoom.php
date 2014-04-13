<?php
	include_once("../scripts/db_script.php");
	if (!isset($_SESSION)){
		session_start();
	}
	if ( isset($_SESSION['role']) ) {
		$access = $_SESSION['role'];
		
		if ( $access == "Manager" ){
			header('Location: ../Manager.php');
		}
		if ( $access == "Employee" ){
			header('Location: ../Employee.php');
		}
	}

		echo "<table><tr>";
		echo "<td><a href=\"../CPE.php\">CPE</a></td>";
		echo "<td><a href=\"../Manager.php\">Manager</a></td>";
		echo "<td><a href=\"../Employee.php\">Employee</a>";
		echo "</tr></table>";
		
		if ( isset( $_SESSION['FacilityID'] ) )
		{
			if ( isset($_POST['AddNewRoom'])) {
					$RoomNumber = $_POST['RoomNumber'];
					$AgeGroup = $_POST['AgeGroup'];
					$RoomExt = $_POST['RoomExt'];
					$FacilityID = $_SESSION['FacilityID'];
					
					if ( $RoomNumber != "" or $RoomExt != "" ) {
						$query = "INSERT INTO Room Values ( '$RoomNumber' , '$AgeGroup', '$RoomExt' )";
						$connection = db_connect();
						
						$result = mysqli_query($connection , $query);
						
						if ( $result )
						{
							$query2 = "INSERT INTO Houses Values ( '$RoomNumber' , '$FacilityID')";
							$result2 = mysqli_query($connection , $query2);
							Echo "Add New Room";
						}
						else {
							printf("Errormessage: %s\n" , mysqli_error($connection));
						}
						
						
					}	
					else {
						echo "Room Number or Room Extension cant be empty";
					}
				mysqli_close($connection);
			}
		}
		
		
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add New Room</title>
    </head>
    <body>

		<form method="POST" action =''>
		<table>
			<th><td>Add New Room</td></th>
			<tr>
				<td>Room Number</td>
				<td><input type="text" id="RoomNumber" name="RoomNumber"/></td>
			</tr>
			<tr>
				<td>Age Group</td>
				<td>
					<select name="AgeGroup">
						<option value="Toddler">Toddler</option>
						<option value="Infant">Infant</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Room Extension</td>
				<td><input type="text" id="RoomExt" name="RoomExt"/></td>
			</tr>
		</table>
		<input name="AddNewRoom" Type="Submit" Value="Add Room">
		<input name="Back" Type="Submit" value="Back">
		</form>
	</body>
</html>
		
