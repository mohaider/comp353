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
        if ((isset($_SESSION['familyID'])))
	{
                if (empty($_SESSION['familyID'])) 
                {
                    die("Page missing family data from FamilyInfo.php");
                }
                  
                
                $con = db_connect();
                
                $familyId = $_SESSION['familyID'];
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
                    <h3>Edit Authorized Contacts For Family:</h3>
                    <table Border='1'>
                    <tr>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Relation Type</th>
                    <th>Contact Incase of Emergency</th>
                    </tr>";
                    $namesArray = array();
                    while($row = mysqli_fetch_array($resultAuthor, MYSQL_BOTH))
                    {
                        $namesArray[] = $row[1];
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
                
                $field = array('Name', 'PhoneNum', 'Relation', 'Emergency');
                
                if(isset($_POST['editContactInfo']))
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
                    $Relation = $_POST['Relation'];
                    $Emergency = $_POST['Emergency'];
                    $edit = $_POST['editContact'];
                    $resultUpdate = mysqli_query($con, "UPDATE AuthorizedContact\n"
                                                     . "SET ContactNumber = '$PhoneNum', Name = '$Name', TypeOfRelationship = '$Relation', IsEmergencyContact = '$Emergency'\n"
                                                     . "WHERE Name = '$edit';");
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
<form id="ContactInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<?php
 echo "Choose Contact To Edit: <select name='editContact'>";
foreach ($namesArray as $value) {
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
Relation Type <select name="Relation">
    <option value="GrandMother">GrandMother</option>
    <option value="GrandFather">GrandFather</option>
    <option value="Aunt">Aunt</option>
    <option value="Uncle">Uncle</option>
    <option value="Other">Other</option>
    </select>
<br />
Contact Incase of Emergency: <select name="Emergency">
    <option value="Yes">Yes</option>
    <option value="No">No</option>
    </select>
<br />
<input name="editContactInfo" type="submit" value="Submit" />
</form>
<FORM METHOD="POST" ACTION="FamilyInfo.php">
<INPUT NAME= "returnFamily" TYPE="submit" VALUE="Return to Information">
</FORM>