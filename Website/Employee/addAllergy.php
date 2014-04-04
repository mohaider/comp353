<?php
session_start();
?>
<html>
    <head>
		<title>Add Guardian Information</title>
    </head>
    <body>
        <H3>Add Allergy To Child</H3>
<?php
include_once("../scripts/db_script.php");
if(isset($_POST['addAllergyInfo']) AND isset($_SESSION['MediNum']))
{
    $fields = array('Name', 'Severity', 'Directions');
    foreach($fields as $index)
    {
        if (empty($_POST[$index])) 
        {
            die("Empty Text Field: ". $index . " Push back button");
        }
    }
    $con = db_connect();
    $mediNum = $_SESSION['MediNum'];
    $name = $_POST['Name'];
    $severity = $_POST['Severity'];
    $directions = $_POST['Directions'];
    $resultInsert = mysqli_query($con, "INSERT INTO Allergies VALUES('$name', '$severity', '$directions')");
    
    if($resultInsert)
    {
        echo "Change Successful <br\>";
    }
    else
    {
         printf("Error message: %s\n", mysqli_error($con));
    }
    $resultInsert = mysqli_query($con, "INSERT INTO AllergySheet VALUES('$name', '$mediNum')");
    if($resultInsert)
    {
        echo "Change Successful";
    }
    else
    {
         printf("Error message: %s\n", mysqli_error($con));
    }
    mysqli_close($con);
}

?>
    <form id="AllergyInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <br />
    Allergy Name: <input name="Name" type="text" id="Name" />
    <br />
    Severity: <input name="Severity" type="text" id="Severity" />
    <br />
    Directions: <input name="Directions" type="text" id="Directions" />
    <br />
    <input name="addAllergyInfo" type="submit" value="Submit" />
    </form>
    <FORM METHOD="POST" ACTION="medicalInfo.php">
    <INPUT NAME= "returnChild" TYPE="submit" VALUE="Return to Information">
    </FORM>
    </body>
</html>
