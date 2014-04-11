
<?php

if(!isset($_SESSION))
    session_start ();
if ($_SESSION['role']=="Employee")
{
header('Location:Employee.PHP');
die();
}
  if(!isset($_SESSION['role']))
{
    header('login.php');
    die();
}
include_once('scripts/employee_script.php');
       $con = db_connect();
	if (isset($_POST["frmAddEmployeeSubmit"]))
	{
		if ($_POST["frmAddEmployeeSubmit"] === "Submit")
		{	
			$empName = $_POST["employeeName"];
			$empAddress = $_POST["employeeAddress"];
			$empRole = $_POST["employeeRole"];
			$empSSN = $_POST["employeeSSN"];
			If ($empName === '' || $empAddress === '')
			{
				echo "<div style='color: red;'>You need enter the employee name, address and social security number!</div>";
			}
			else if (strlen($empName) > 30 || strlen($empAddress) > 30 || strlen($empSSN) != 9)
			{
				echo "<div style='color: red;'>Name and should not be longer than 30 characters and <br />Social security number must be 9 characters</div>";
			}
			else
			{
                            $empID = addEmployee($empName, $empAddress, $empRole, $empSSN);
                                # mysql query Get a distinct EmpID from login
                             if ($_SESSION['role']!="CPE") 
                             {
                                $empCurrent = $_SESSION['empID'];
                                $resultFacility = mysqli_query($con, "SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empCurrent';");
                                if(!$resultFacility)
                                {
                                    print_r(mysqli_error($con));
                                }
                                $facility = mysqli_fetch_row($resultFacility);
                                $resultEmpList = mysqli_query($con, "INSERT INTO EmployeeLists VALUES('$empID', '$facility[0]');");
                                if(!$resultEmpList)
                                {
                                    print_r(mysqli_error($con));
                                }
                             }
                                if ($_SESSION['role']=="CPE")
                                {
                                    
                                    //mysql query to insert new employee into the list of employees of a facility
                                    
                                    $sqlEmployee =  "INSERT INTO EmployeeLists(FacilityID,EmpID) "
                                            ." VALUES(".$_POST['facilityID'].",".$empID.") ";
                                    $resultOfSQLEmployeeQuery = mysqli_query($con,$sqlEmployee);
                                    if(!$resultOfSQLEmployeeQuery)
                                {
                                    print_r(mysqli_error($con));
                                }
                                    db_close($con);
                                }
				if ($empID > 0) {
					echo "<div>The employee " . $empName . " has been created. His employee ID is " . $empID . ". His default password is formed with the first four letters of his name + his employee ID.</div>";
				}
			}
		}
	}
?>
<html>
	<head>
		<title>
                    Add New Employees
                <?php
                if($_SESSION['role'] == "CPE" )
                    echo "Or New Manager";
                ?>
                </title>
	</head>
	<body>
		<form name="frmAddEmployee" method="POST" action="">
			Enter the information of the new employee:
			<br />
			Name: <input type="text" name="employeeName" />
			<br />
			Address: <input type="text" name="employeeAddress" />
			<br />
			Role:
                        <select name="employeeRole">
                            <option value="Employee">Employee</option>
                            <?php
                            if ($_SESSION['role']=="CPE")
                            echo "<option value='Manager'>Manager</option>";
                             ?>
                        </select>
                        <?php
                         if ($_SESSION['role'] == "CPE"){
                            $con = db_connect();
                        //MySql retrieve a list of facilities to assign the new manager/employee to
                            $sqlFacilityQuery = "SELECT ID,Address FROM Facility";
                            //       $existingFacilities = mysqli_query($con, $existingFacQuery);
                            $sqlExistingFacilityResults  = mysqli_query($con, $sqlFacilityQuery);
                            echo "<br>Assign new employee to a facility";
                            echo "<select name='facilityID'>";
                            while (($row = mysqli_fetch_array($sqlExistingFacilityResults, MYSQL_BOTH)) )
                                {
                                echo "<option value=".$row[0].">".$row[1]."</option>";
				}
                            echo "</select>";
                         }
                        ?>
			<br />
			Social Security Number: <input type="text" name="employeeSSN" />
			<br />
			<input type="submit" value="Submit" name="frmAddEmployeeSubmit" />
		</form>
	</body>
</html> 