<?php
	include_once("scripts/staff_script.php");
	include_once('scripts/room_script.php');
	include_once('scripts/db_script.php');
	
	$roomID = "H666";
	$connection = db_connect();
	
	if (isset($_POST["submitFrmRoomInfo"]))
	{
		if ($_POST["submitFrmRoomInfo"] === "Submit")
		{
			if (getNumberOfChildren($connection, $roomID) !== 0)
			{ 
				$message = "The room must be empty of children before changing the age group";
				echo "<script type='text/javascript'>alert('$message');</script>";
				//echo "To modify the age group of a room, the room must be empty"; 
			}
			else
			{
				$ageGroup = $_POST["sltAgeGroupName"];
				$connection = db_connect();
				modifyAgeGroup($connection, $roomID, $ageGroup); 
			}
		}
	}
?>

<html>
	<head>
		<title>Room Information</title>
		<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.10.4.custom.min.css" />
	</head>
	<body>
		<b>Room Information</b>
		<br />
		<form name="frmRoomInfo" method="POST" action="<?php $_SERVER['PHP_SELF'];?>">
			Room #: <?php echo $roomID; ?>
			<br />
			Facility #: <?php echo getFacilityID($connection, $roomID); ?>
			<br />
			Age group of the room:
			<select id="sltAgeGroup" name="sltAgeGroupName">
				<?php 
					if (getAgeGroup($connection, $roomID) === "Infant")
					{
						echo "<option selected='selected'>Infants</option><option>Toddlers</option></select>";
					}
					else if (getAgeGroup($connection, $roomID) === "Toddler")
					{
						echo "<option>Infants</option><option selected='selected'>Toddlers</option></select>";
					}
					else
					{
						echo "<option selected='selected'>Infants</option><option>Toddlers</option></select>";
					}
				?>
			<br />
			<input type="submit" name="submitFrmRoomInfo" value="Submit" />
		</form>
		<br />
		<br />
		Room Information:
		<br />
		<?php
			$empList = getStaffList($connection, $roomID);
			$empNumber = count($empList);
		?>
		Staff member: <?php echo $empNumber ?> (<a href="#" onclick="openStaffMemberBox();">View</a>)
		<div id="staffDialog" style="width: 800px;">
			<table>
				<tr>
					<th align="left" style="width:200px;">Employee ID</th>
					<th align="left" style="width:200px;">Name</th>
					<th align="left" style="width:200px;">&nbsp;</th>
				</tr>
				<?php
					
					for ($i = 0; $i < count($empList); ++$i)
					{
						echo "<tr>".PHP_EOL;
						echo "<td style=\"width: 200px;\">".$empList[$i][0]."</td>".PHP_EOL;
						echo "<td style=\"width: 200px;\">".$empList[$i][1]."</td>".PHP_EOL;
						echo "<td style=\"width: 200px;\"><a href=\"#\">More</a></td>".PHP_EOL;
						echo "</tr>".PHP_EOL;
					}
				?>
			</table>
		</div>
		<script type="text/javascript">
			$(function() {
				$("#staffDialog").dialog({
					width: 800,
					modal: true,
					autoOpen: false,
					title: "Staff List",
				});
			});
			
			function openStaffMemberBox()
			{
				$("#staffDialog").dialog("open");
			}
		</script>
		<br />
		<?php
			$childList = getChildrenList($connection, $roomID);
			$childNumber = count($childList);
		?>
		Children: <?php echo $childNumber ?> / MAX NUMBER OF CHILDREN (<a href="#" onclick="openChildrenBox();">View</a>)
		<div id="childrenDialog">
			<table>
				<tr>
					<th align="left" style="width:200px;">Medicare Number</th>
					<th align="left" style="width:200px;">Name</th>
					<th align="left" style="width:200px;">&nbsp;</th>
				</tr>
				<?php
					
					for ($i = 0; $i < count($childList); ++$i)
					{
						echo "<tr>".PHP_EOL;
						echo "<td style=\"width: 200px;\">".$childList[$i][0]."</td>".PHP_EOL;
						echo "<td style=\"width: 200px;\">".$childList[$i][1]."</td>".PHP_EOL;
						echo "<td style=\"width: 200px;\"><a href=\"#\">More</a></td>".PHP_EOL;
						echo "</tr>".PHP_EOL;
					}
				?>
			</table>
		</div>
		<script type="text/javascript">
			$(function() {
				$("#childrenDialog").dialog({
					modal: true,
					autoOpen: false,
					title: "Children List",
					width: 700,
				});
			});
			
			function openChildrenBox()
			{
				$("#childrenDialog").dialog("open");
			}
		</script>
		<br />
	</body>
</html>
<?php
	db_close($connection);
?>