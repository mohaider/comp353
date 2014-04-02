<?php
	include_once('scripts/employee_script.php');
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
				if ($empID > 0)
				{
					echo "<div>The employee " . $empName . " has been created. His employee ID is " . $empID . ". His default password is formed with the first four letters of his name + his employee ID.</div>";
				}
			}
		}
	}
?>
<html>
	<head>
		<title>Add New Employees</title>
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
				<option value="Manager">Manager</option>
				<option value="CPE">CPE</option>
			</select>
			<br />
			Social Security Number: <input type="text" name="employeeSSN" />
			<br />
			<input type="submit" value="Submit" name="frmAddEmployeeSubmit" />
		</form>
	</body>
</html>