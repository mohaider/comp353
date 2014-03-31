<html>
	<head></head>
	<body>
		<?php
			$connection = mysql_connect("localhost", "root", "");
			if (!$connection)
				die ("Could not establish connection" . mysql_error());
				
			if (!mysql_select_db("Daycare"))
				die ("Could not connect to database" . mysql_error());
			
			/*$result = mysql_query("SELECT * FROM Child;");
			if (!$result)
				die("NOOO!!" . mysql_error());
			echo mysql_result($result, 1);*/
			
			//mysql_query("INSERT INTO Employee VALUES ('Louis-Max', 'Gendron', '123 False Street', 'CPE', '1999-01-01', '', 951236874);");
			
			$adminPassword = "GEND3";
			$cryptedPassword = md5($adminPassword);
			//echo $cryptedPassword;
			
			#echo "INSERT INTO Logins (EmpID, Password) VALUES (3, " . $cryptedPassword . ");";
			$result = mysql_query("INSERT INTO Logins (EmpID, Password) VALUES (3, '" . $cryptedPassword . "');");
			if (!$result)
				die ("FAIL " . mysql_error());
			
			mysql_close($connection);
		?>
	<body>
</html>