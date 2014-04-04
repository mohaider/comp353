<?php
session_start();
?>
<html>
    <head>
		<title>Add Guardian Information</title>
    </head>
    <body>
        <H3>Add Guardian to Family</H3>
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
        
        $field = array('Name', 'PhoneNum', 'Address', 'Type');
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
            $resultGuardian = mysqli_query($con, "CALL insertGuardian('$Name', '$Address', '$PhoneNum', '$type');");
            if($resultGuardian)
            {
                echo "Change Successful";
            }
            else
            {
                 printf("Errormessage: %s\n", mysqli_error($con));
            }
            $guardianID = mysqli_fetch_array($resultGuardian);
            cleanDatabaseBuffer($con);
            $resultGuardian = mysqli_query($con, "INSERT INTO PrimaryCaretaker VALUES('$familyId', '$guardianID[0]]');");
            if($resultGuardian)
            {
                echo "Change Successful";
            }
            else
            {
                 printf("Errormessage: %s\n", mysqli_error($con));
            }
            cleanDatabaseBuffer($con);
        }
        mysqli_close($con);
}
?>
<form id="GuardianInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
        