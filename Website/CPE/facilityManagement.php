<?php
if (!isset($_SESSION))
    session_start();

//if (isset($_POST['facilitySubmissionEdit'])) {
//    header('Location:facilityManagement.php');
//    die();
//}

if (isset($_POST['newFacility'])) {
    header('Location:newFacility.PHP');
    die();
}
if (isset($_POST['returnToCPEMenu'])) {
    header('Location:../CPE.php');
    die();
}
?>




<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Facility Information</title>
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
        include_once("../scripts/db_script.php");
        $con = db_connect();


        //check if post variables aren't set 
        if (!isset($_POST['facilitySubmissionEdit']) & !isset($_POST['submitFacilityMods']) &!isset($_POST['terminateFacility']) ) {
           //MYSQL query for facility information display 
            $existingFacQuery = " SELECT * FROM FACILITY  ";
            //mySQL query for finding the number of rooms in a facility
            $numOfRoomsQuery = "SELECT COUNT( FacilityID )"
                    . " FROM houses "
                    . "WHERE FacilityID =";


            $existingFacilities = mysqli_query($con, $existingFacQuery);
            echo "<form name='modifyFacility' method='POST' action=" . $_SERVER['PHP_SELF'] . " onsubmit= \"return validateForm();\">";
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


            $idArray = array();
            while ($row = mysqli_fetch_array($existingFacilities, MYSQL_BOTH)) {


                //fetch the room count
                $resultOfRoomCount = mysqli_query($con, $numOfRoomsQuery . $row['ID']);
                $count = mysqli_fetch_row($resultOfRoomCount);
                $idArray[] = $row['ID'];


                echo " 
                    <tr>
                    <td><input type='radio' name='facilitySelected' id='facilityRadioButton' value=" . $row['ID'] . "></td>
                    <td>" . $row['ID'] . "</td>
                    <td>" . $row['Address'] . "</td>
                    <td>" . $row['Type'] . "</td>
                    <td>" . $row['PhoneNum'] . "</td>
                    <td>" . $count[0] . "</td>"
                . "</tr>";
            }
            echo"</table>";

            echo " <input type='submit' name='facilitySubmissionEdit' value='Modify Selected Facility'>"
            . "</form>";
        } 
        else if (isset($_POST['submitFacilityMods'])) 
            {
            $facilityID = $_POST['facilityId'];
            $newFacilityAddress = $_POST['newAddress'];
            $newFacilityNum = $_POST['newPhoneNumber'];
            $con = db_connect();
            //mysql query to update facility with new phone number and address
            $resultUpdate = mysqli_query($con, "UPDATE facility\n"
                                                     . "SET PhoneNum = '$newFacilityNum', Address = '$newFacilityAddress'\n"
                                                     . "WHERE ID = '$facilityID';");
            
            //destroy previous post values
            unset($_POST['facilitySubmissionEdit'],$_POST['submitFacilityMods']);
            db_close($con);
            //redirect
            header('Location:facilityManagement.php');
            
        }
        else if (isset($_POST['terminateFacility']))
        {
            $con = db_connect();
            $facilityID = $_POST['facilityId'];
            //mysql query to delete facility where id = facilityID
            $resultDelete = mysqli_query($con,"DELETE FROM facility WHERE ID =".$facilityID);
            db_close($con);
            header('Location:facilityManagement.php');
            
        }
            else {
            echo "<form name='editFacilityMods' method='POST' action=" . $_SERVER['PHP_SELF'] .">";
            //<input type='radio' name='facilitySelected' id='facilityRadioButton' value=" . $row['ID'] . ">
            echo "Facility ID: " . "<input type ='radio' name='facilityId' id='facilityNumId'" 
                    . " value =" . $_POST['facilitySelected'] . " checked>".$_POST['facilitySelected'] ."</br>";
            echo "Facility Address: <input type='text' name='newAddress' id='Address'></br>";
            echo "New Facility tel#: <input type='text' name='newPhoneNumber' id='telNo'></br>";
            
            echo " <input type='submit' name='submitFacilityMods' value='Update Facility Information'>";
            echo "<input type='submit' name='terminateFacility' value='Terminate Facility'>";
            echo"</form></br>";
                        
            
        }
        ?>
        <br>
        <FORM METHOD="POST" ACTION=''>
            <INPUT NAME= "newFacility" TYPE="submit" VALUE="Create New Facility">
                <INPUT NAME= "returnToCPEMenu" TYPE="submit" VALUE="Return to CPE menu">
                    </FORM>