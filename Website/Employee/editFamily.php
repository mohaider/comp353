<?php
session_start();
?>
<html>
    <head>
		<title>Edit Family Information</title>
    </head>
    <body>
<?php
        include_once("../scripts/db_script.php");
        if ((isset($_SESSION['LastName']) AND isset($_SESSION['PhoneNum'])))
	{
                $field = array('LastName', 'PhoneNum');
                
                $LastName = $_SESSION['LastName'];
                $PhoneNum = $_SESSION['PhoneNum'];
                
                if (empty($_SESSION['LastName']) OR empty($_SESSION['PhoneNum'])) 
                {
                    die("Page missing family data from FamilyInfo.php");
                }
                  
                
                $con = db_connect();
                
                # Get EmpID from login
                $empID = $_SESSION['empID'];
                $resultFacility = mysqli_query($con, "SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empID';");
                if(!$resultFacility)
                {
                    print_r(mysqli_error($con));
                }
                $facility = mysqli_fetch_row($resultFacility);
                
                if($_SESSION['role'] == "CPE")
                {
                    echo "<p>CPE</p>";
                    $resultFamily = mysqli_query($con, "SELECT *"
                            . "                         FROM Family"
                            . "                         WHERE LastName = '$LastName' AND PhoneNum = '$PhoneNum'");
                }
                else
                {
                    echo "<p>OTHER</p>";
                    $resultFamily = mysqli_query($con, "CALL getFamilyFromFacility('$facility[0]', '$LastName', '$PhoneNum');");
                }
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
                }
                if(isset($_POST['editFamilyInfo']))
                {
                    foreach($field as $index)
                    {
                        if (empty($_POST[$index])) 
                        {
                            die("Empty Text Field: ". $index . " Push back button");
                        }
                    }
                    $LastName = $_POST['LastName'];
                    $PhoneNum = $_POST['PhoneNum'];
                    $_SESSION['LastName'] = $LastName;
                    $_SESSION['PhoneNum'] = $PhoneNum;
                    $resultUpdate = mysqli_query($con, "UPDATE Family"
                            . "                         SET LastName = '$LastName', PhoneNum = '$PhoneNum'"
                            . "                         WHERE ID = '$familyId'");
                    
                    if($resultUpdate)
                    {
                        echo "Change Successful";
                    }
                    else
                    {
                         printf("Errormessage: %s\n", mysqli_error($con));
                    }
                }
                mysqli_close($con);
        }
?>
<form id="FamilyInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <br />
        Family Name: <input name="LastName" type="text" id="LastName" />
        <br />
        Phone Number: <input name="PhoneNum" type="text" id="PhoneNum" />
        <br />
        <input name="editFamilyInfo" type="submit" value="Submit" />
</form>
<FORM METHOD="POST" ACTION="FamilyInfo.php">
<INPUT NAME= "returnFamily" TYPE="submit" VALUE="Return to Information">
</FORM>

