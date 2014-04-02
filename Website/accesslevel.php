<?php
	if (!isset($_SESSION)){
	session_start();
	}
	
	if ( isset($_SESSION['role']) ) {

		$access = $_SESSION['role'];


		echo "<table><tr>";
		if ( $access == "CPE"){
			echo "<td><a href=\"CPE.php\">CPE</a></td>";
			echo "<td><a href=\"Manager.php\">Manager</a></td>";
			echo "<td><a href=\"Employee.php\">Employee</a>";
		}
		elseif( $access == "Manager") {
			echo "<td><a href=\"Manager.php\">Manager</a></td>";
			echo "<td><a href=\"Employee.php\">Employee</a></td>";
		}
		echo "</tr></table>";
	}
?>
