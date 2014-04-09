<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role'])) {
    header('Location:../login.php');
    die();
}

if (!isset($_SESSION['facilitySelected'])) {
    header('Location:cpeExistingEmployeeMenu.php');
}

if (isset($_POST['existingManager'])) {
    unset($_POST);
    header('Location:cpeExistingEmployeeMenu.php');
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
        <title>Employee management</title>
        <script type="text/javascript">

            function validateForm()
            {
                var radios = document.getElementsByName("employeeSelected");
                var validForm = false;
                var i = 0;

                while (!validForm && i < radios.length)
                {
                    if (radios[i].checked)
                        validForm = true;
                    i++;
                }
                if (!validForm)
                    alert("Please make sure to select an employee before submitting")
                return validForm;

            }
        </script>
    </head>
    <body>
        <html>
            <head>
                <title>Employee management</title>
            </head>
            <body>
<?php
include_once("../scripts/db_script.php");
$con = db_connect();
if (isset($_POST['employeeTerminate'])) {
    cleanDatabaseBuffer($con);
    $facilitySelected = $_SESSION['facilitySelected'];
    $sqlDate = date("Y-m-d");
    //mySQL to update employee information to show his role as NLE: no longer employed
    $sqlEmployeeUpdate = " UPDATE employee "
            . "SET EndDate =" . "'" . $sqlDate . "'" . ","
            . "Role= 'NLE' "
            . "WHERE EmpID= " . $_POST['employeeSelected'];

    //mySQL query to delete employee from the employee list table

    $sqlEmployeelistDeletion = " DELETE FROM employeelists "
            . "WHERE EmpID= " . $_POST['employeeSelected'];
    $sqlUpdateEmployeeTable = mysqli_query($con, $sqlEmployeeUpdate);
    if (!$sqlUpdateEmployeeTable) {
        print_r(mysqli_error($con));
    }

    cleanDatabaseBuffer($con);
    $sqlDeleteFromEmployeeList = mysqli_query($con, $sqlEmployeelistDeletion);
    if (!$sqlDeleteFromEmployeeList) {
        print_r(mysqli_error($con));
    }
    $_SESSION['employeeisTerminated'] = true;
    header('Location:cpeExistingEmployee.php');
}

if (!isset($_POST['employeeSelected']) || $_SESSION['employeeisTerminated'] 
        || !isset($_POST['employeeSubmit'])|| !isset($_POST['submitEmployeeInformation'])) {
    $_SESSION['employeeisTerminated'] = false;
    $_SESSION['employeeModified'] = false;
    $facilitySelected = $_SESSION['facilitySelected'];
    $_POST['facilitySelected'] = $facilitySelected;

    //MYSQL query for employee retrieval of a facility
    $SqlEmployees = "SELECT * FROM (employee NATURAL JOIN 
            (SELECT * FROM employeelists WHERE facilityID =" . $facilitySelected . " ) 
            AS tableOfEmps)
            WHERE Role<>'CPE' AND Role<>'NLE'";


    $facilityEmployees = mysqli_query($con, $SqlEmployees);

    if (!$facilityEmployees) {
        print_r(mysqli_error($con));
    }
    echo "<form name = 'employeeSelection' method = 'POST' action=" . $_SERVER['PHP_SELF'] .
    "  onsubmit= \"return validateForm();\">";
    echo "<h3>Employee List</h3>";
    echo "<table Border='1'>
            <tr>\n
            <th></th>\n
            <th>Employee ID</th>\n
            <th>Name</th>\n
            <th>Address</th>\n
            <th>Role</th>\n
            <th>Start Date</th>\n
            </tr>\n";



    while ($row = mysqli_fetch_array($facilityEmployees, MYSQL_BOTH)) {

        echo " 
                    <tr>
                    <td><input type='radio' name='employeeSelected'  value=" . $row['EmpID'] . "></td>
                    <td>" . $row['EmpID'] . "</td>
                    <td>" . $row['Name'] . "</td>
                    <td>" . $row['Address'] . "</td>
                    <td>" . $row['Role'] . "</td>
                    <td>" . $row['StartDate'] . "</td>
                    </tr>";
    }
    echo "</table>";
    echo " <input type='submit' name='employeeSubmit' value='Modify Employee Information'>";
    echo " <input type='submit' name='employeeTerminate' value='Terminate Employee'>";
    echo"</form>";
    db_close($con);
}

if (isset($_POST['employeeSubmit']) & !isset($_POST['submitEmployeeInformation'])) {
    
    $currentEmp = $_POST['employeeSelected'];


    //mySQL query get a list of facilities 
    $availableFacility = "SELECT * FROM facility "; 
//            . "WHERE ID <> (SELECT FacilityID "
//            . "FROM employeelists "
//            . "WHERE EmpID =".$currentEmp .") ";
    $con = db_connect();
    $sqlResultOfFacilityQuery = mysqli_query($con, $availableFacility);
    if (!$sqlResultOfFacilityQuery) {
        print_r(mysqli_error($con));
    }
    echo "<form name='editEmployees' method='POST' action=" . $_SERVER['PHP_SELF'] . ">";
    //<input type='radio' name='facilitySelected' id='facilityRadioButton' value=" . $row['ID'] . ">
    echo "Employee ID: " . "<input type ='radio' name='empID' "
    . " value =" . $currentEmp . " checked>  " . $currentEmp . "</br>";
    echo "Move to Different Facility <select name='newFacility'>";
    while ($row = mysqli_fetch_array($sqlResultOfFacilityQuery, MYSQL_BOTH)) {
        echo "<option value =" . $row['ID'] . ">" . $row['Address'] . "</option>";
    }
    echo "</select></br>";
    echo "Change Employee role <select name='newRole'>";
    echo "<option value = 'Manager'>Manager</option>";
    echo "<option value = 'Employee'>Employee</option>";
    echo "</select></br>";

    echo " <input type='submit' name='submitEmployeeInformation' value='Update Employee Information'>";

    echo"</form></br>";

    
}
   if (isset($_POST['submitEmployeeInformation'])) {
       $currentEmp = $_POST['empID'];
       $newFacility = $_POST['newFacility'];
       $newRole = $_POST['newRole'];
       $con = db_connect();
       
       //mysql query to update the employee table
       $sqlUpdateEmployeeTable = "UPDATE employee "
               . "SET Role="."'".$newRole."'"
               . " WHERE EmpID=".$currentEmp;
       
        $sqlUpdateEmployeeTableResult = mysqli_query($con, $sqlUpdateEmployeeTable);
        if (!$sqlUpdateEmployeeTableResult)
        {
           
            print_r(mysqli_error($con));
        }
        
        cleanDatabaseBuffer($con);
       
//mysql query to get old facility(this will be used to check if the employee needs to be removed from 
       $sqlGetOldFacility  = "SELECT FacilityID "
               . "FROM employeelists "
               . "WHERE EmpID = ".$currentEmp;

      $sqlGetOldFacilityResults = mysqli_query($con, $sqlGetOldFacility);
    
    if (!$sqlUpdateEmployeeTableResult) { 
        print_r(mysqli_error($con));
    }
    cleanDatabaseBuffer($con);
    
      $oldFac = mysqli_fetch_array($sqlGetOldFacilityResults, MYSQL_BOTH);
      $oldFacility = $oldFac['FacilityID'];
    
    
    
    //check if its a different facility 
    if ($oldFacility != $newFacility) {
        cleanDatabaseBuffer($con);
        //mysql query to update the employee list to reflect the new facility 
        $sqlUpdateEmployeelist = "UPDATE employeelists "
                . "SET FacilityID= " . $newFacility . " "
                . "WHERE EmpID=" . $currentEmp;

        $sqlUpdateEmployeelistResults = mysqli_query($con, $sqlUpdateEmployeelist);

        if (!$sqlUpdateEmployeelistResults) {
            print_r(mysqli_error($con));
        }
        cleanDatabaseBuffer($con);
        //mysql query to remove employee from rooms

        $sqlDeleteFromSupervises = "DELETE FROM supervises "
                . "WHERE EmpID=" . $currentEmp;
       
        $sqlDeleteFromSupervisesResults = mysqli_query($con, $sqlUpdateEmployeelist);

        if (!$sqlDeleteFromSupervisesResults) {
            print_r(mysqli_error($con));
        }
        
    }



    //mysql query to remove employee from rooms
       
        header("Location:cpeExistingEmployeeMenu.php");
   }
?>


                <form method="POST" action=''>

                   
                        <input type="submit" name="existingManager" value=" Modify existing management(or employees) "> 
                            
                                </form>			
                                </body>


                                </html>