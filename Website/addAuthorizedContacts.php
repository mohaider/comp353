<?php
session_start();
?>
<html>
    <head>
		<title>Emergency Contact</title>
    </head>
    <body>
        <H3>Add New Emergency Contact</H3>
<?php
        include_once("scripts/db_script.php");
        if (empty($_SESSION['familyID'])) 
        {
            die("Page missing family data from FamilyInfo.php");
        }
        $con = db_connect();
                
        $familyId = $_SESSION['familyID'];
        
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
            $resultAuthor = mysqli_query($con, "INSERT INTO AuthorizedContact VALUES('$PhoneNum', '$Name', '$Relation', '$Emergency');");
            if($resultAuthor)
            {
                echo "Change Successful";
            }
            else
            {
                 printf("Errormessage: %s\n", mysqli_error($con));
            }
            cleanDatabaseBuffer($con);
            mysqli_free_result($resultAuthor);
            echo $familyId;                    
            $resultAuthor = mysqli_query($con, "INSERT INTO IsAuthorized VALUES('$PhoneNum', '$familyId');");
            if($resultAuthor)
            {
                echo "Change Successful";
            }
            else
            {
                 printf("Errormessage: %s\n", mysqli_error($con));
            }
            cleanDatabaseBuffer($con);
            mysqli_free_result($resultAuthor);
        }
        mysqli_close($con);
?>
<form id="ContactInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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