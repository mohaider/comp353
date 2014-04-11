<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['role'])) {
    header('Location:../login.php');
}
if ($_SESSION['role'] != 'CPE') {
     header('Location:../' .$_SESSION['role'].'PHP');   
}

if(isset($_POST))
{
    if(isset($_POST['childSelected']))
    {
        $_SESSION['childSelected'] = $_POST['childSelected'];
        header('Location:newChildFacilityRelocaton.php');
    }
}
echo "<table><tr>";
echo "<td><a href=\"../CPE.php\">CPE</a></td>";
echo "<td><a href=\"../Manager.php\">Manager</a></td>";
echo "<td><a href=\"../Employee.php\">Employee</a>";
echo "</tr></table>";
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Child Relocation</title>
        <script type="text/javascript">

            function validateForm()
            {
                var radios = document.getElementsByName("childSelected");
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
   
<?php
include_once("../scripts/db_script.php");
$con = db_connect();
$facilitySelected = $_SESSION['facilitySelected'];


    //mysql query to retrieve a list of children based off the facility they are registered into
    //SELECT * FROM child NATURAL JOIN (SELECT MedicareNum FROM `registrationsheet` WHERE FacilityID=1) AS T1
      $sqlChildListQuery = "SELECT * FROM Child "
              . "NATURAL JOIN (SELECT MedicareNum "
              . "FROM RegistrationSheet "
              . "WHERE FacilityID=".$facilitySelected.") "
              . "AS T1";
      $sqlChildListQueryResults = mysqli_query($con, $sqlChildListQuery);
      if(!$sqlChildListQueryResults)
          print_r(mysqli_error($con));

      
 echo "<form name = 'childSelection' method = 'POST' action=" . $_SERVER['PHP_SELF'] .
    "  onsubmit= \"return validateForm();\">";
    echo "<h3>Child List</h3>";
    echo "<table Border='1'>
            <tr>\n
            <th></th>\n
            <th>Name</th>\n
            <th>Medicare Number</th>\n
            <th>Sex</th>\n
            <th>Age Group</th>\n
            </tr>\n";



    while ($row = mysqli_fetch_array($sqlChildListQueryResults, MYSQL_BOTH)) {
        $medicareNum = $row['MedicareNum'];
        echo " 
                    <tr>
                    <td><input type='radio' name='childSelected'  value='$medicareNum'></td> 
                    <td>" . $row['Name'] . "</td>                    
                    <td>" . $row['MedicareNum'] . "</td>
                    <td>" . $row['SEX'] . "</td>
                    <td>" . $row['AgeGroup'] . "</td>
                    </tr>";
    }
    echo "</table>";
    echo " <input type='submit' name='relocateChild' value='RelocateChild'>";
    echo"</form>";
   


   
?>



    <a href="childFacilityRelocationMenu.php">Return to facility selection</a>
</html>