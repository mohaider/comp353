<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['role'])) {
    header('../login.php');
}
if ($_SESSION['role'] != 'CPE') {
     header('Location:../' .$_SESSION['role'].'PHP');
    
}
echo "<table><tr>";
echo "<td><a href=\"../CPE.php\">CPE</a></td>";
echo "<td><a href=\"../Manager.php\">Manager</a></td>";
echo "<td><a href=\"../Employee.php\">Employee</a>";
echo "</tr></table>";
?>

<?php
 if(isset($_POST['facilitySubmissionEdit']))
 {
     include_once("../scripts/db_script.php"); 
     $con = db_connect();
     $newFacility = $_POST['facilitySelected'];
     $currentChild = $_SESSION['childSelected'];
     //mysql query to update child's registration sheet to new facility
     $sqlUpdateQuery = "UPDATE registrationsheet "
             . "SET FacilityID=".$newFacility." "
             . "WHERE MedicareNum='$currentChild'";
     
     $sqlUpdateQueryResults = mysqli_query($con, $sqlUpdateQuery);
     if (!$sqlUpdateQueryResults)
         print_r(mysqli_error($con));
     cleanDatabaseBuffer($con);
     
     //query to remove child from seated into
     $sqlDeleteChildFromRoom = "DELETE FROM seatedinto "
             . "WHERE MedicareNum='$currentChild'";
     $sqlDeleteChildFromRoomResults = mysqli_query($con,$sqlDeleteChildFromRoom);
     if (! $sqlDeleteChildFromRoomResults)
         print_r(mysqli_error ($con));
     
     
     
     header('Location:childFacilityRelocationMenu.php');
 }

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Child Relocation</title>
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
                    alert("Please make sure to select a child before submitting")
                return validForm;

            }
        </script>
    </head>
    <body>
        <h1>List of Other sites</h1>
  <?php
  include_once("../scripts/db_script.php");
$con = db_connect();
$facilitySelected = $_SESSION['facilitySelected'];
$childSelected = $_SESSION['childSelected'];


//mysql query to select facilities that don't have the child as a registrant

$sqlListofFacilities = "SELECT * FROM facility "
        . "WHERE ID <>( SELECT FacilityID "
        . "FROM registrationsheet "
        . "WHERE MedicareNum  ='$childSelected')";
$sqlListofFacilitiesResult = mysqli_query($con, $sqlListofFacilities);

if (!$sqlListofFacilitiesResult)
    print_r($con);



    echo "<form name='newFacilitySelection' method='POST' action=" . $_SERVER['PHP_SELF'] . " onsubmit= \"return validateForm();\">";
            echo "
                <h3>Existing Facility</h3>
                <table Border='1'>
                <tr>
                <th></th>
                <th>Facility ID</th>
                <th>Address</th>
                <th>Facility Type</th>
                <th>Facility&#39s Phone Number</th>
                </tr>";
            
            while( $row = mysqli_fetch_array($sqlListofFacilitiesResult, MYSQL_BOTH))
            {
                
                echo " 
                    <tr>
                    <td><input type='radio' name='facilitySelected' id='facilityRadioButton' value=" . $row['ID'] . "></td>
                    <td>" . $row['ID'] . "</td>
                    <td>" . $row['Address'] . "</td>
                    <td>" . $row['Type'] . "</td>
                    <td>" . $row['PhoneNum'] . "</td>
                    </tr>";
            }
           echo"</table>";

            echo " <input type='submit' name='facilitySubmissionEdit' value='Submit'>"
            . "</form>";

    
  
  
  
  ?>

        <a href="cpeExistingChild.php">Return to child selection menu</a>
    </body>
</html>