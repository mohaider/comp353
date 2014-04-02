<?php
	function db_connect()
	{
		$connection = mysqli_connect("localhost", "root", "", "Daycare") or die ("Could not establish connection" . mysql_error());
		
		return $connection;
	}
	
	function cleanDatabaseBuffer($connection)
	{
		while(mysqli_more_results($connection))
		{
			mysqli_next_result($connection);
		}
	}
	
	function db_close($connection)
	{
		mysqli_close($connection);
	}
?>