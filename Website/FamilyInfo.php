<html>
    <head>
		<title>Family Information</title>
    </head>
    <body>
<?php

        if (isset($_POST["submitFamilyInfo"]))
	{
		if ($_POST["submitFamilyInfo"] == "Submit")
		{	
			$LastName = $_POST["LastName"];
			$PhoneNum = $_POST["PhoneNum"];
		
	
                        $connection = mysql_connect("localhost", "root", "root");
                                        if (!$connection)
                                                die ("Could not establish connection" . mysql_error());

                                        if (!mysql_select_db("Daycare"))
                                                die ("Could not connect to database" . mysql_error());
                        # Get EmpID from login
                        $empID = 4;
                        $resultFacility = mysql_query("SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empID';");
                        $facility = mysql_fetch_row($resultFacility);
                        $resultFamily = mysql_query("CALL getFamilyFromFacility('$facility[0]]', '$LastName', '$PhoneNum');");
                        
                        echo $resultFamily;
                        echo "<br/>";
                        if(mysql_num_rows($resultFamily) == 0)
                        {
                            echo "Family doesn't exist!";
                        }
                        else
                        {
                            echo "<table Border='1'>
                            <tr>
                            <th>ID</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            </tr>";

                            while($row = mysql_fetch_array($resultFamily, MYSQL_BOTH))
                            {
                                echo "<tr>
                                <td>" . $row[0] . "</td>
                                <td>" . $row[1] . "</td>
                                <td>" . $row[2] . "</td>
                                </tr>";
                            }
                            echo "</table>";
                        }
                        
                        mysql_free_result($resultFamily);
                        mysql_close($connection);
                 }
        }
        else 
        {
                ?>
		<form id="FamilyInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<br />
			Family Name: <input name="LastName" type="text" id="LastName" />
			<br />
			Phone Number: <input name="PhoneNum" type="text" id="PhoneNum" />
			<br />
			<input name="submitFamilyInfo" type="submit" value="Submit" />
		</form>
                <?php
        }

?>
	</body>
</html>