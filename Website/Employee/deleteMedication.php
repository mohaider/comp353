<?php
session_start();
?>

<html>
    <head>
		<title>Delete Medical Information</title>
    </head>
    <body>
        <H3> Delete Drug Information</H3>
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
        $drugArray = array();
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
            $drugArray[] = $row[0];
            echo "<tr>
            <td>" . $row[1] . "</td>
            <td>" . $row[0] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        echo "</table>";
        if(isset($_POST['deleteDrugInfo']))
        {
            $drug = $_POST['deleteMedical'];
            $resultDelete = mysqli_query($con, "DELETE FROM MedicalSheet WHERE DrugCode = '$drug' AND MedicareNum = '$mediNum'");
            if($resultDelete)
            {
                echo "Change Successful";
            }
            else
            {
                print_r(mysqli_error($con));
            }
        }
    }
}
?>
        <form id="MedicalInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <?php
        echo "Choose Drug: <select name='deleteMedical'>";
        foreach ($drugArray as $value) {
              echo ""
            . "<option value='$value'>'$value'</option>";
        }
        echo "</select>";
        ?>
        <br />
        <input name="deleteDrugInfo" type="submit" value="Submit" />
        </form>
        <FORM METHOD="POST" ACTION="medicalInfo.php">
        <INPUT NAME= "returnMedical" TYPE="submit" VALUE="Return to Information">
        </FORM>
    </body>
</html>