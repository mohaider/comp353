<?php
	function db_connect()
	{
		$connection = mysql_connect("localhost", "root", "");
		if (!$connection)
			die ("Could not establish connection" . mysql_error());
			
		//select DB
		if (!mysql_select_db("Daycare"))
			die ("Could not connect to database" . mysql_error());
		
		return $connection;
	}
	
	function db_close($connection)
	{
		mysql_close($connection);
	}
?>