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
    header('Location:../login.php');
}
if ($_SESSION['role'] != 'CPE') {
     header('Location:../' .$_SESSION['role'].'PHP');
}

function checkifFull($facType,$facilityID)
{
    $isFull = false;
//
   if ($facType =='Home')
   {
       return getIfHomeIsFull($facilityID);
   }
   else if ($facType =='Center')
   {
       return getIfCenterIsFull($facilityID);
       
   }
 
   return $isFull;
    
}

function getIfHomeIsFull($facilityID)
{
    $dbConnect = db_connect();
    $sqlHomeCenterQuery="SELECT COUNT(FacilityID) FROM "
            . "RegistrationSheet WHERE FacilityID= ".$facilityID;
    $sqlHomeCenterQueryResult = mysqli_query($dbConnect, $sqlHomeCenterQuery);
    $total = mysqli_fetch_row($sqlHomeCenterQueryResult);
    print_r(mysqli_error($dbConnect));
    if ($total[0] >=9 )
        return true;
    else {
       //check for infants
       cleanDatabaseBuffer($dbConnect);
       $sqlInfantCount = "
        SELECT COUNT(AgeGroup) FROM RegistrationSheet WHERE FacilityID=".$facilityID. " AND AgeGroup='Infant'";
       $sqlInfantCountResult = mysqli_query($dbConnect, $sqlInfantCount);
       $total = mysqli_fetch_row($sqlInfantCountResult);
       return ($total[0] == 4); 

    }
    
    
    
}
function getIfCenterIsFull($facilityID)
{
    
    $dbConnect = db_connect();
    
    $sqlInfantCount = "
        SELECT COUNT(AgeGroup) FROM RegistrationSheet WHERE FacilityID=" . $facilityID . " AND AgeGroup='Infant'";
    $sqlInfantCountResult = mysqli_query($dbConnect, $sqlInfantCount);
    $infantTotal = mysqli_fetch_row($sqlInfantCountResult);

    $sqlToddlerCount = "
        SELECT COUNT(AgeGroup) FROM RegistrationSheet WHERE FacilityID=" . $facilityID . " AND AgeGroup='Toddler'";
    $sqlToddlerCountResult = mysqli_query($dbConnect, $sqlToddlerCount);
    $toddlerTotal = mysqli_fetch_row($sqlToddlerCountResult);
    
    $sqlEmployeeCount = "SELECT COUNT(EmpID) FROM EmployeeLists WHERE FacilityID= ".$facilityID;
        
    $sqlEmployeeCountResult = mysqli_query($dbConnect, $sqlEmployeeCount);
    $employeeTotal = mysqli_fetch_row($sqlEmployeeCountResult);
    
    if ($employeeTotal[0] == 0 )
        return true;
    if (($infantTotal[0] >= 5* $employeeTotal[0] OR $toddlerTotal[0] >= 5* $employeeTotal[0] )
          OR ( ($infantTotal[0] + $toddlerTotal[0] )>=8* $employeeTotal[0] ) )
        return true;
    
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
     
     

     $sqlUpdateQuery = "UPDATE RegistrationSheet "
             . "SET FacilityID=".$newFacility." "
             . "WHERE MedicareNum='$currentChild'";
     
     $sqlUpdateQueryResults = mysqli_query($con, $sqlUpdateQuery);
     if (!$sqlUpdateQueryResults)
         print_r(mysqli_error($con));
     cleanDatabaseBuffer($con);
     
     //query to remove child from seated into
     $sqlDeleteChildFromRoom = "DELETE FROM SeatedInto "
             . "WHERE MedicareNum='$currentChild'";
     $sqlDeleteChildFromRoomResults = mysqli_query($con,$sqlDeleteChildFromRoom);
     if (! $sqlDeleteChildFromRoomResults)
         print_r(mysqli_error ($con));
     
     
     echo "<meta http-equiv='refresh'  content='0; url=https://clipper.encs.concordia.ca/~hac353_4/CPE/childFacilityRelocationMenu.php' />";
     //header('Location:childFacilityRelocationMenu.php');
     }
     //ratio is full

 

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


$sqlFactype = "SELECT Type FROM Facility WHERE ID=".$facilitySelected;
$sqlFactypeResult = mysqli_query($con, $sqlFactype);
$facilityTypeRow = mysqli_fetch_array($sqlFactypeResult, MYSQL_BOTH);
$facilityType = $facilityTypeRow[0];
$_SESSION['facilityType'] = $facilityType;



//mysql query to select facilities that don't have the child as a registrant

$sqlListofFacilities = "SELECT * FROM Facility "
        . "WHERE ID <>( SELECT FacilityID "
        . "FROM RegistrationSheet "
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
                $type  = $row['Type'];
                $ID     = $row['ID'];
                echo " <tr>";
               
                    if (!checkifFull($type,$ID ) )
                        {
                        echo "<td><input type='radio' name='facilitySelected' id='facilityRadioButton' value=" . $ID . "></td>";
                        }
                        else {continue;}
                    
                    echo "
                    <td>" . $ID . "</td>
                    <td>" . $row['Address'] . "</td>
                    <td>" . $type . "</td>
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
