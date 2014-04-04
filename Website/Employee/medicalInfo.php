<?php
session_start();
if(isset($_POST["addMedication"]))
{
    header('Location: addMedication.php');
    die();
}
if(isset($_POST["deleteMedication"]))
{
    header('Location: deleteMedication.php');
    die();
}
?>

<html>
    <head>
		<title>Edit Child Information</title>
    </head>
    <body>
        <H3> Medical and Emergency Information</H3>
<?php
include_once("../scripts/db_script.php");
if(isset($_SESSION['MediNum']))
{
    $mediNum = $_SESSION['MediNum'];
    $con = db_connect();
    echo "Information for ". $mediNum ."<br/>";
    //MYSQL Query
    $resultMedical = mysqli_query($con, "SELECT *"
            . "                          FROM Medication"
            . "                          JOIN MedicalSheet"
            . "                          ON Medication.DrugCode = MedicalSheet.DrugCode"
            . "                          WHERE MedicalSheet.MedicareNum = '$mediNum'");
    if(!resultMedical)
    {
        print_r(mysqli_error($con));
    }
    if(mysqli_num_rows($resultMedical) == 0)
    {
        echo "No Medical Problems!";
    }
    else
    {
        echo "
        <h3>Medical Information:</h3>
        <table Border='1'>
        <tr>
        <th>Medication Name</th>
        <th>Drug Code</th>
        <th>Administration</th>
        </tr>";
        while($row = mysqli_fetch_array($resultMedical, MYSQL_BOTH))
        {
            echo "<tr>
            <td>" . $row[1] . "</td>
            <td>" . $row[0] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        echo "</table>";
    }
    ?>
        <FORM METHOD="POST" ACTION="">
        <INPUT NAME= "addMedication" TYPE="submit" VALUE="Add New Medication">
        <INPUT NAME= "deleteMedication" TYPE="submit" VALUE="Delete Medication">
        </FORM>
   <?php
    //MYSQL Query
    $resultAllergy = mysqli_query($con, "SELECT *"
                                      . "FROM Allergies "
                                      . "JOIN AllergySheet "
                                      . "ON Allergies.AllergyType = AllergySheet.AllergyType "
                                      . "WHERE AllergySheet.MedicareNum = '$mediNum'");
    if(!resultAllergy)
    {
        print_r(mysqli_error($con));
    }
    if(mysqli_num_rows($resultAllergy) == 0)
    {
        echo "No Allergy Problems!";
    }
    else
    {
        echo "
        <h3>Allergy Information:</h3>
        <table Border='1'>
        <tr><th>Allergy Name</th>
        <th>Severity</th>
        <th>Directions</th>
        </tr>";
        while($row = mysqli_fetch_array($resultAllergy, MYSQL_BOTH))
        {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        echo "</table>";
    }
     ?>
        <FORM METHOD="POST" ACTION="addAllergy.php">
        <INPUT NAME= "addAllergy" TYPE="submit" VALUE="Add New Allergy">
        </FORM>
   <?php
    //MYSQL Query
    $resultFamily = mysqli_query($con, "SELECT DISTINCT(FamilyID) "
            . "                         FROM ChildOf"
            . "                         WHERE MedicareNum = '$mediNum'");
    if(!$resultFamily)
    {
        print_r(mysqli_error($con));
    }
    $family = mysqli_fetch_row($resultFamily);
    //MYSQL Query
    $resultEmergency = mysqli_query($con,   "SELECT *\n"
                                          . "FROM AuthorizedContact AS ac\n"
                                          . "JOIN IsAuthorized\n"
                                          . "ON ac.ContactNumber = IsAuthorized.ContactNumber\n"
                                          . "WHERE IsAuthorized.FamilyID = '$family[0]' AND ac.IsEmergencyContact = 'Yes'");
    if(!$resultAuthor)
    {
        print_r(mysqli_error($con));
    }
    if(mysqli_num_rows($resultEmergency) == 0)
    {
        echo "No Emergency Contacts!";
    }
    else
    {
        echo "
        <h3>Emergency Contacts:</h3>
        <table Border='1'>
        <tr>
        <th>Name</th>
        <th>Phone Number</th>
        <th>Relation Type</th>
        </tr>";
        while($row = mysqli_fetch_array($resultEmergency, MYSQL_BOTH))
        {
            echo "<tr>
            <td>" . $row[1] . "</td>
            <td>" . $row[0] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        echo "</table>";
    }
    cleanDatabaseBuffer($con);
}
?>
    <FORM METHOD="POST" ACTION="ChildInfo.php">
    <INPUT NAME= "returnChild" TYPE="submit" VALUE="Return to Information">
    </FORM>
    </body>
</html>