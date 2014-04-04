<?php
session_start();
?>
<html>
    <head>
		<title>Edit Guardian Information</title>
    </head>
    <body>
<?php
        include_once("scripts/db_script.php");
        if ((isset($_SESSION['familyID'])))
	{
                if (empty($_SESSION['familyID'])) 
                {
                    die("Page missing family data from FamilyInfo.php");
                }
                  
                $con = db_connect();
                $familyId = $_SESSION['familyID'];
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
                $idArray = array();
                while($row = mysqli_fetch_array($resultGuardians, MYSQL_BOTH))
                {
                    $idArray[] = $row[0];
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
                mysqli_free_result($resultAuthor);
                
                $field = array('Name', 'Address', 'PhoneNum', 'Type');
                
                if(isset($_POST['editGuardianInfo']))
                {
                    foreach($field as $index)
                    {
                        if (empty($_POST[$index])) 
                        {
                            die("Empty Text Field: ". $index . " Push back button");
                        }
                    }
                    
                    $Name = $_POST['Name'];
                    $PhoneNum = $_POST['PhoneNum'];
                    $type = $_POST['Type'];
                    $Address = $_POST['Address'];
                    $edit = $_POST['editGuardian'];
                    $resultUpdate = mysqli_query($con, "UPDATE Guardian\n"
                                                     . "SET PhoneNumber = '$PhoneNum', Name = '$Name', Address = '$Address', GuardianOrParent = '$type'\n"
                                                     . "WHERE ID = '$edit';");
                    if($resultUpdate)
                    {
                        echo "Change Successful";
                        printf("Errormessage: %s\n", mysqli_error($con));
                    }
                    else
                    {
                         printf("Errormessage: %s\n", mysqli_error($con));
                    }
                }
                mysqli_close($con);
        }

?>
<form id="GuardianInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<?php
echo "Choose Guardian To Edit: <select name='editGuardian'>";
foreach ($idArray as $value) {
      echo ""
    . "<option value='$value'>'$value'</option>";
}
echo "</select>";
?>
<br />
Contact Name: <input name="Name" type="text" id="Name" />
<br />
New Phone Number: <input name="PhoneNum" type="text" id="PhoneNum" />
<br />
New Address: <input name="Address" type="text" id="Address" />
<br />
Contact Incase of Emergency: <select name="Type">
    <option value="Parent">Parent</option>
    <option value="Guardian">Guardian</option>
    </select>
<br />
<input name="editGuardianInfo" type="submit" value="Submit" />
</form>
<FORM METHOD="POST" ACTION="FamilyInfo.php">
<INPUT NAME= "returnFamily" TYPE="submit" VALUE="Return to Information">
</FORM>