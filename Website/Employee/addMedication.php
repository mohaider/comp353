<?php
session_start();
?>
<html>
    <head>
		<title>Add Medication Information</title>
    </head>
    <body>
        <H3>Add Medication To Child</H3>
<?php
include_once("../scripts/db_script.php");
if(isset($_POST['addMedicalInfo']) AND isset($_SESSION['MediNum']))
{
    $fields = array('Name', 'DrugCode', 'Directions');
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
    $drugCode = $_POST['DrugCode'];
    $directions = $_POST['Directions'];
    $resultInsert = mysqli_query($con, "INSERT INTO Medication VALUES('$drugCode', '$name', '$directions')");
    
    if($resultInsert)
    {
        echo "Change Successful <br\>";
    }
    else
    {
         printf("Error message: %s\n", mysqli_error($con));
    }
    $resultInsert = mysqli_query($con, "INSERT INTO MedicalSheet VALUES('$drugCode', '$mediNum')");
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
    <form id="MedicalInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <br />
    Medication Name: <input name="Name" type="text" id="Name" />
    <br />
    Drug Code: <input name="DrugCode" type="text" id="Severity" />
    <br />
    Directions: <input name="Directions" type="text" id="Directions" />
    <br />
    <input name="addMedicalInfo" type="submit" value="Submit" />
    </form>
    <FORM METHOD="POST" ACTION="medicalInfo.php">
    <INPUT NAME= "returnChild" TYPE="submit" VALUE="Return to Information">
    </FORM>
    </body>
</html>
