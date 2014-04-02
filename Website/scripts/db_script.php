<?php
	function db_connect()
	{
		$connection = new mysqli("localhost", "root", "", "Daycare");
		if ($connection->connect_errno)
		{
			die ($connection->connect_error);
		}
	
		return $connection;
	}
	
	function db_close($connection)
	{
		mysqli_close($connection);
	}
?>