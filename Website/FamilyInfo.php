<?php 
session_start();
?>
<html>
    <head>
		<title>Family Information</title>
    </head>
    <body>
<?php
        include_once("scripts/db_script.php");
        if (isset($_POST["submitFamilyInfo"]) || (isset($_SESSION['LastName']) AND isset($_SESSION['PhoneNum'])))
	{
                $field = array('LastName', 'PhoneNum');
                
                if(isset($_POST["submitFamilyInfo"]))
                {
                    foreach($field as $index)
                    {
                        if (empty($_POST[$index])) 
                        {
                            die("Empty Text Field: ". $index . "Push back button");
                        }
                    }
                    $_SESSION['LastName'] = $_POST["LastName"];
                    $_SESSION['PhoneNum'] = $_POST["PhoneNum"];
                }
                
                $LastName = $_SESSION['LastName'];
                $PhoneNum = $_SESSION['PhoneNum'];
                
                $con = db_connect();
                
                # Get EmpID from login
                $empID = 4;
                $resultFacility = mysqli_query($con, "SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empID';");
                if(!$resultFacility)
                {
                    print_r(mysqli_error($con));
                }
                $facility = mysqli_fetch_row($resultFacility);
                
                $resultFamily = mysqli_query($con, "CALL getFamilyFromFacility('$facility[0]', '$LastName', '$PhoneNum');");
                if(!$resultFamily)
                {
                    print_r(mysqli_error($con));
                }
                if(mysqli_num_rows($resultFamily) == NULL AND !isset($_SESSION['familyID']))
                {
                    echo "Family doesn't exist!";
                }
                else
                {
                    echo "
                    <h3>Family Information:</h3>
                    <table Border='1'>
                    <tr>
                    <th>ID</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    </tr>";

                    while($row = mysqli_fetch_array($resultFamily, MYSQL_BOTH))
                    {
                        $familyId = $row[0];
                        echo "<tr>
                        <td>" . $row[0] . "</td>
                        <td>" . $row[1] . "</td>
                        <td>" . $row[2] . "</td>
                        </tr>";
                    }
                    cleanDatabaseBuffer($con);
                    echo "</table>";
                    $resultGuardians = mysqli_query($con, "SELECT *\n"
                                                        . "FROM Guardian\n"
                                                        . "JOIN PrimaryCaretaker AS pc\n"
                                                        . "ON ID = pc.GuardianID\n"
                                                        . "WHERE pc.FamilyID = '$familyId'");
                    if(!$resultGuardians)
                    {
                        print_r(mysqli_error($con));
                    }
                    echo "
                    <h3>Family Guardians:</h3>
                    <table Border='1'>
                    <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Individual Phone Number</th>
                    <th>Guardian Type</th>
                    </tr>";

                    while($row = mysqli_fetch_array($resultGuardians, MYSQL_BOTH))
                    {
                        echo "<tr>
                        <td>" . $row[0] . "</td>
                        <td>" . $row[1] . "</td>
                        <td>" . $row[2] . "</td>
                        <td>" . $row[3] . "</td>
                        <td>" . $row[4] . "</td>
                        </tr>";
                    }
                    cleanDatabaseBuffer($con);
                    echo "</table>";
                    if($_REQUEST['ChildInfo'])
                    {
                        $resultChildren = mysqli_query($con, "SELECT *\n"
                                                           . "FROM Child\n"
                                                           . "JOIN ChildOf\n"
                                                           . "ON Child.MedicareNum = ChildOf.MedicareNum\n"
                                                           . "WHERE ChildOf.FamilyID = '$familyId'");
                        if(!$resultChildren)
                        {
                            print_r(mysqli_error($con));
                        }
                       echo "
                        <h3>Children of Family:</h3>
                        <table Border='1'>
                        <tr>
                        <th>Medicare Number</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Sex</th>
                        <th>Age Group</th>
                        </tr>";
                        while($row = mysqli_fetch_array($resultChildren, MYSQL_BOTH))
                        {
                            echo "<tr>
                            <td>" . $row[0] . "</td>
                            <td>" . $row[3] . "</td>
                            <td>" . $row[2] . "</td>
                            <td>" . $row[1] . "</td>
                            <td>" . $row[4] . "</td>
                            </tr>";
                        }
                        echo "</table>";
                        cleanDatabaseBuffer($con);
                        mysqli_free_result($resultChildren);
                    }
                }
                mysqli_free_result($resultFacility);
                mysqli_free_result($resultFamily);
                mysqli_free_result($resultGuardians);
               
                mysqli_close($con);
                 
                ?>
                <FORM METHOD="LINK" ACTION="resetFamilySearch.php">
                <INPUT NAME= "Reset" TYPE="submit" VALUE="Reset Search">
                </FORM>
                <FORM METHOD="POST" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
                <INPUT NAME= "ChildInfo" TYPE="submit" VALUE="View Children Info">
                </FORM>
                <?php
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