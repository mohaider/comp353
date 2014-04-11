<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['role'])) {
    header('Location:../login.php');
    die();
}
if (isset($_POST['facilitySubmissionEdit'])) {
    $_SESSION['facilitySelected'] = $_POST['facilitySelected'];
    header('Location: cpeExistingEmployee.php');
}
var_dump($_POST);
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Employee management</title>
        <script type="text/javascript">

            function validateForm()
            {
                var radios = document.getElementsByName("facilitySelected");
                var validForm = false;
                var i = 0;

                while (!validForm && i < radios.length)
                {
                    if (radios[i].checked)
                        validForm = true;
                    i++;
                }
                if (!validForm)
                    alert("Please make sure to select a facility before submitting")
                return validForm;

            }
        </script>
    </head>
    <body>

<?php
echo "<table><tr>";
echo "<td><a href=\"../CPE.php\">CPE</a></td>";
echo "<td><a href=\"../Manager.php\">Manager</a></td>";
echo "<td><a href=\"../Employee.php\">Employee</a>";
echo "</tr></table>";

include_once("../scripts/db_script.php");
$con = db_connect();
//MYSQL query for facility information display 
$existingFacQuery = " SELECT * FROM Facility  ";
//mySQL query for finding the number of rooms in a facility
$numOfRoomsQuery = "SELECT COUNT( FacilityID )"
        . " FROM Houses "
        . "WHERE FacilityID =";

$existingFacilities = mysqli_query($con, $existingFacQuery);
echo "<form name='facilitySelection' method='POST' action=". $_SERVER['PHP_SELF'] . " onsubmit= \"return validateForm()\">";
echo "
                <h3>Existing Facility</h3>
                <table Border='1'>
                <tr>
                <th></th>
                <th>Facility ID</th>
                <th>Address</th>
                <th>Facility Type</th>
                <th>Facility&#39s Phone Number</th>
                <th>Room Count</th>
                </tr>";

//display facility information
var_dump($_SESSION);
echo "Post  info"; 

$idArray = array();
while ($row = mysqli_fetch_array($existingFacilities, MYSQL_BOTH)) {


    //fetch the room count
    $resultOfRoomCount = mysqli_query($con, $numOfRoomsQuery . $row['ID']);
    $count = mysqli_fetch_row($resultOfRoomCount);
    $idArray[] = $row['ID'];


    echo "           <tr>
                    <td><input type='radio' name='facilitySelected' value=" . $row['ID'] . "></td>
                    <td>" . $row['ID'] . "</td>
                    <td>" . $row['Address'] . "</td>
                    <td>" . $row['Type'] . "</td>
                    <td>" . $row['PhoneNum'] . "</td>
                    <td>" . $count[0] . "</td>"
    . "</tr>";
}
echo"</table>";

echo " <input type='submit' name='facilitySubmissionEdit' value='Submit'>"
 . "</form>";
?>	
    </body>
</html>