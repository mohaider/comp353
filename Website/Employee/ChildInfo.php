<?php
session_start();
if(isset($_POST["editChild"]))
{
    header('Location: editChild.php');
    die();
}
if(isset($_POST["emergencyChild"]))
{
    header('Location: medicalInfo.php');
    die();
}
if(isset($_POST["roomChild"]))
{
    header('Location: roomChange.php');
    die();
}
if(isset($_POST["removeChild"]))
{
    header('Location: removeFromFacility.php');
    die();
}
?>

<html>
    <head>
		<title>Child Information</title>
    </head>
    <body>
        <H3> Child Information Page</H3>
<?php
include_once("../scripts/db_script.php");
if(isset($_POST['submitChildInfo']) || $_SESSION['MediNum'])
{
    if(isset($_POST["submitChildInfo"]))
    {
        if (empty($_POST['MediNum'])) 
        {
            die("Empty Medicare Number Field Push back button");
        }
        else
        {
            $_SESSION['MediNum'] = $_POST["MediNum"];
        }
    }
    
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
            $_SESSION['AgeGroup'] = $row[4];
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
        ?>
        <FORM METHOD="POST" ACTION="">
        <INPUT NAME= "editChild" TYPE="submit" VALUE="Edit">
        <INPUT NAME= "emergencyChild" TYPE="submit" VALUE="Medical Information">
        <INPUT NAME= "removeChild" TYPE="submit" VALUE="Remove From Facility">
        </FORM>
        <?php
        //MYSQL Query
        $resultRoom = mysqli_query($con, "SELECT * "
                . "                       FROM Room"
                . "                       JOIN SeatedInto"
                . "                       ON Room.RoomNum = SeatedInto.RoomNum"
                . "                       WHERE SeatedInto.MedicareNum = '$mediNum'");
        
        echo "
        <h3>Room Information:</h3>
        <table Border='1'>
        <tr>
        <th>Facility ID</th>
        <th>Room Number</th>
        <th>Age Group</th>
        <th>Extension number</th>
        </tr>";
        $_SESSION['facilityID'] = $facility[0];
        while($row = mysqli_fetch_array($resultRoom, MYSQL_BOTH))
        {
            echo "<tr>
            <td>" . $facility[0] . "</td>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        cleanDatabaseBuffer($con);
        echo "</table>";
        ?>
        <FORM METHOD="POST" ACTION="">
        <INPUT NAME= "roomChild" TYPE="submit" VALUE="Change Room">
        </FORM>
        <?php
        If(!isset($_REQUEST['GuardianInfo']))
        {   
            ?>
            <FORM METHOD="POST" ACTION="">
            <INPUT NAME= "GuardianInfo" TYPE="submit" VALUE="View Guardian Info">
            </FORM>
            <?php
        }
        if(isset($_REQUEST['GuardianInfo']))
        {
            $resultFamily = mysqli_query($con, "SELECT DISTINCT(FamilyID) "
                    . "                         FROM ChildOf"
                    . "                         WHERE MedicareNum = '$mediNum'");
            if(!$resultFamily)
            {
                print_r(mysqli_error($con));
            }
            $family = mysqli_fetch_row($resultFamily);
            
            $resultGuardians = mysqli_query($con, "SELECT *\n"
                                                . "FROM Guardian\n"
                                                . "JOIN PrimaryCaretaker AS pc\n"
                                                . "ON ID = pc.GuardianID\n"
                                                . "WHERE pc.FamilyID = '$family[0]'");
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
            ?>
            <FORM METHOD="POST" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
            <INPUT NAME= "Hide" TYPE="submit" VALUE="Hide Guardian Info">
            </FORM><?php
            
        }
        If(!isset($_REQUEST['AuthorInfo']))
        {   
            ?>
            <FORM METHOD="POST" ACTION="">
            <INPUT NAME= "AuthorInfo" TYPE="submit" VALUE="View Contact Info">
            </FORM>
            <?php
        }
        if(isset($_REQUEST['AuthorInfo']))
        {
            $resultFamily = mysqli_query($con, "SELECT DISTINCT(FamilyID) "
                    . "                         FROM ChildOf"
                    . "                         WHERE MedicareNum = '$mediNum'");
            if(!$resultFamily)
            {
                print_r(mysqli_error($con));
            }
            $family = mysqli_fetch_row($resultFamily);
            $resultAuthor = mysqli_query($con,   "SELECT *\n"
                                                           . "FROM AuthorizedContact AS ac\n"
                                                           . "JOIN IsAuthorized\n"
                                                           . "ON ac.ContactNumber = IsAuthorized.ContactNumber\n"
                                                           . "WHERE IsAuthorized.FamilyID = '$family[0]'");
            if(!$resultAuthor)
            {
                print_r(mysqli_error($con));
            }
            if(mysqli_num_rows($resultAuthor) == 0)
            {
                echo "No Emergency Contacts!";
            }
            else
            {
                echo "
                <h3>Authorized Contacts For Family:</h3>
                <table Border='1'>
                <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Relation Type</th>
                <th>Contact Incase of Emergency</th>
                </tr>";
                while($row = mysqli_fetch_array($resultAuthor, MYSQL_BOTH))
                {
                    echo "<tr>
                    <td>" . $row[1] . "</td>
                    <td>" . $row[0] . "</td>
                    <td>" . $row[2] . "</td>
                    <td>" . $row[3] . "</td>
                    </tr>";
                }
                echo "</table>";
                cleanDatabaseBuffer($con);
                ?>
                <FORM METHOD="POST" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
                <INPUT NAME= "HideAuthor" TYPE="submit" VALUE="Hide Contact Info">
                </FORM><?php
            }
            cleanDatabaseBuffer($con);
        }
    }
    ?>
    <FORM METHOD="LINK" ACTION="resetChildSearch.php">
    <INPUT NAME= "Reset" TYPE="submit" VALUE="Reset Search">
    </FORM>
    <?php
}
else
{
    ?>
    <form id="ChildInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <br />
        Medicare Number: <input name="MediNum" type="text" id="MediNum" />
        <br />
        <input name="submitChildInfo" type="submit" value="Submit" />
     </form>
     <?php
}
?>

    </body>
</html>