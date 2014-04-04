<?php
session_start();
?>

<html>
    <head>
		<title>Edit Child Information</title>
    </head>
    <body>
        <H3> Edit Child Page</H3>
<?php
include_once("../scripts/db_script.php");
if(isset($_SESSION['MediNum']))
{
    $mediNum = $_SESSION['MediNum'];
    $con = db_connect();
    
    $empID = $_SESSION['empID'];
    //MYSQL Query
    $resultFacility = mysqli_query($con, "SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empID';");
    if(!$resultFacility)
    {
        print_r(mysqli_error($con));
    }
    $facility = mysqli_fetch_row($resultFacility);
    //MYSQL Query
    $resultChildInfo = mysqli_query($con, "SELECT * \n"
                                        . "FROM Child\n"
                                        . "JOIN RegistrationSheet\n"
                                        . "ON Child.MedicareNum = RegistrationSheet.MedicareNum\n"
                                        . "WHERE RegistrationSheet.FacilityID = '$facility[0]' AND Child.MedicareNum = '$mediNum'");
    if(!$resultChildInfo)
    {
        print_r(mysqli_error($con));
    }
    if(mysqli_num_rows($resultChildInfo) == 0)
    {
        echo "Child doesn't exist!";
    }
    else
    {
        echo "
        <h3>Child Information:</h3>
        <table Border='1'>
        <tr>
        <th>Medicare Number</th>
        <th>Name</th>
        <th>DOB</th>
        <th>SEX</th>
        <th>Age Group</th>
        </tr>";

        while($row = mysqli_fetch_array($resultChildInfo, MYSQL_BOTH))
        {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[3] . "</td>
            <td>" . $row[2] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[4] . "</td>
            </tr>";
        }
        cleanDatabaseBuffer($con);
        echo "</table>";
        
        if(isset($_POST['submitChildInfo']))
        {
            $field = array('Name','DOB','Gender','AgeGroup');
            foreach($field as $index)
            {
                if (empty($_POST[$index])) 
                {
                    die("Empty Text Field: ". $index . " Push back button");
                }
            }
            $name = $_POST['Name'];
            $dob = $_POST['DOB'];
            $gender = $_POST['Gender'];
            $age = $_POST['AgeGroup'];
            //MYSQL Query
            $resultUpdate = mysqli_query($con, "UPDATE Child"
                    . "                         SET Name = '$name', DOB = '$dob', SEX = '$gender', AgeGroup = '$age'"
                    . "                         WHERE MedicareNum = '$mediNum'");
            if($resultUpdate)
            {
                echo "Change Successful";
            }
            else
            {
                 printf("Errormessage: %s\n", mysqli_error($con));
            }
        }
    }
    mysqli_close($con);
}
?>
    <form id="ChildInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <br />
        Name: <input name="Name" type="text" id="Name" />
        <br />
        Date Of Birth(yyyy-mm-dd): <input name="DOB" type="text" id="DOB" />
        <br />
        Gender: <select name="Gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                </select>
        <br />
        Age Group: <select name="AgeGroup">
                <option value="Infant">Infant</option>
                <option value="Toddler">Toddler</option>
                </select>
        <br />
        <input name="submitChildInfo" type="submit" value="Submit" />
    </form>
    <FORM METHOD="POST" ACTION="ChildInfo.php">
    <INPUT NAME= "returnChild" TYPE="submit" VALUE="Return to Information">
    </FORM>
    </body>
</html>