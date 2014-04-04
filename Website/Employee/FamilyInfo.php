<?php 
session_start();
if(isset($_POST["EditAuthor"]))
{
    header('Location:editAuthorizedContacts.php');
    die();
}
if(isset($_POST["AddAuthor"]))
{
    header('Location: addAuthorizedContacts.php');
    die();
}
if(isset($_POST["RemoveAuthor"]))
{
    header('Location: removeContact.php');
    die();
}
if(isset($_POST["addGuardian"]))
{
    header('Location: addGuardians.php');
    die();
}
if(isset($_POST["editGuardian"]))
{
    header('Location: editGuardian.php');
    die();
}
if(isset($_POST["removeGuardian"]))
{
    header('Location: removeGuardian.php');
    die();
}
if(isset($_POST["returnEmployee"]))
{
    header('Location: ../Employee.php');
    die();
}
?>
<html>
    <head>
		<title>Family Information</title>
    </head>
    <body>
        <H3> Family Information Page</H3>
<?php
        include_once("../scripts/db_script.php");
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
                $empID = $_SESSION['empID'];
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
                if(mysqli_num_rows($resultFamily) == NULL)
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
                        $_SESSION['familyID'] = $row[0];
                        $familyId = $row[0];
                        echo "<tr>
                        <td>" . $row[0] . "</td>
                        <td>" . $row[1] . "</td>
                        <td>" . $row[2] . "</td>
                        </tr>";
                    }
                    cleanDatabaseBuffer($con);
                    echo "</table>";
                    if($_SESSION['access'] == "Manager")
                    {
                        ?>
                        <FORM METHOD="POST" ACTION="editFamily.php">
                        <INPUT NAME= "editFamily" TYPE="submit" VALUE="Edit">
                        </FORM>
                        <?php
                    }
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
                    ?>
                    <FORM METHOD="POST" ACTION="">
                    <INPUT NAME= "editGuardian" TYPE="submit" VALUE="Edit">
                    <INPUT NAME= "addGuardian" TYPE="submit" VALUE="Add Guardian">
                    <INPUT NAME= "removeGuardian" TYPE="submit" VALUE="Remove Guardian">
                    </FORM>
                    <?php
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
                        ?>
                        <FORM METHOD="POST" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <INPUT NAME= "Hide" TYPE="submit" VALUE="Hide Children Info">
                        </FORM><?php
                    }
                    if($_REQUEST['AuthorizedInfo'])
                    {
                        $resultAuthor = mysqli_query($con,   "SELECT *\n"
                                                           . "FROM AuthorizedContact AS ac\n"
                                                           . "JOIN IsAuthorized\n"
                                                           . "ON ac.ContactNumber = IsAuthorized.ContactNumber\n"
                                                           . "WHERE IsAuthorized.FamilyID = '$familyId'");
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
                        }
                        cleanDatabaseBuffer($con);
                        mysqli_free_result($resultAuthor);
                        ?>
                        <FORM METHOD="POST" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <INPUT NAME= "HideAuthor" TYPE="submit" VALUE="Hide Contact Info">
                        <INPUT NAME= "EditAuthor" TYPE="submit" VALUE="Edit Contact Info">
                        <INPUT NAME= "AddAuthor" TYPE="submit" VALUE="Add New Contact">
                        <INPUT NAME= "RemoveAuthor" TYPE="submit" VALUE="Remove Contact">
                        </FORM><?php
                    }
                    mysqli_free_result($resultFamily);
                    mysqli_free_result($resultGuardians);
                }
                mysqli_free_result($resultFacility);
               
                mysqli_close($con);
                ?>
                <FORM METHOD="LINK" ACTION="resetFamilySearch.php">
                <INPUT NAME= "Reset" TYPE="submit" VALUE="Reset Search">
                </FORM>
                <FORM METHOD="POST" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
                <?php
                If(!$_REQUEST['ChildInfo'])
                {   
                    ?>
                    <INPUT NAME= "ChildInfo" TYPE="submit" VALUE="View Children Info">
                    <?php
                }
                If(!$_REQUEST['AuthorizedInfo'])
                {   
                    ?>
                    <INPUT NAME= "AuthorizedInfo" TYPE="submit" VALUE="View Contact Info">
                    
                    <?php
                }
                ?></FORM><?php
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
        
<FORM METHOD="POST" ACTION="">
<INPUT NAME= "returnEmployee" TYPE="submit" VALUE="Return to Menu">
</FORM>
	</body>
</html>