<?php
session_start();
if(isset($_POST["returnEmployee"]))
{
    header('Location: ../Employee.php');
    die();
}
function diffDates($startDate, $endDate)
{
    $start = strtotime($startDate);
    $end = strtotime($endDate);

    $count = 0;

    while(date('Y-m-d', $start) < date('Y-m-d', $end)){
      $count += date('N', $start) < 6 ? 1 : 0;
      $start = strtotime("+1 day", $start);
    }
    if($count > 0)
        $count = $count - 1;
    else
        $count = 0;
    return $count;
    
}
?>

<html>
    <head>
		<title>Employee Home Page</title>
    </head>
    <body><h3>Invoice Page </h3>
<?php
include_once("../scripts/db_script.php");
if(isset($_POST['submitFamilyInfo']) || (isset($_SESSION['LastName']) AND isset($_SESSION['PhoneNum'])))
{
        
        if(isset($_POST["submitFamilyInfo"]))
        {
            foreach($field as $index)
            {
                if (empty($_POST[$index])) 
                {
                    die("Empty Text Field: ". $index . "Push back button");
                }
            }
            $_SESSION['LastName'] = $_POST["LastName"];
            $_SESSION['PhoneNum'] = $_POST["PhoneNum"];
        }
        $LastName = $_SESSION['LastName'];
        $PhoneNum = $_SESSION['PhoneNum'];

        $con = db_connect();

        # Get EmpID from login
        $empID = $_SESSION['empID'];
        $resultFacility = mysqli_query($con, "SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empID';");
        if(!$resultFacility)
        {
            print_r(mysqli_error($con));
        }
        $facility = mysqli_fetch_row($resultFacility);
        if($_SESSION['role'] == "CPE")
        {
            echo "<p>CPE</p>";
            $resultFamily = mysqli_query($con, "SELECT *"
                    . "                         FROM Family"
                    . "                         WHERE LastName = '$LastName' AND PhoneNum = '$PhoneNum'");
        }
        else
        {
            echo "<p>OTHER</p>";
            $resultFamily = mysqli_query($con, "CALL getFamilyFromFacility('$facility[0]', '$LastName', '$PhoneNum');");
        }

        if(!$resultFamily)
        {
            print_r(mysqli_error($con));
        }
        if(mysqli_num_rows($resultFamily) == NULL)
        {
            echo "Family doesn't exist!";
        }
        else
        {
                $row = mysqli_fetch_row($resultFamily);
                $_SESSION['familyID'] = $row[0];
                $familyId = $row[0];
        }
        cleanDatabaseBuffer($con);
        
        $resultDates = mysqli_query($con, "SELECT MedicareNum, StartDate, EndDate"
                . "                        FROM RegistrationSheet"
                . "                        WHERE MedicareNum IN (SELECT Child.MedicareNum "
                . "                                              FROM Child "
                . "                                              JOIN Childof "
                . "                                              ON Child.MedicareNum = ChildOf.MedicareNum "
                . "                                              WHERE ChildOf.FamilyID = '$familyId') ");

        if(!$resultDates)
        {
            print_r(mysqli_error($con));
        }
        $chargeDays;

        $today = date("Y-m-d");

        while($row = mysqli_fetch_array($resultDates, MYSQL_BOTH))
        {
            echo "<p> Today: ". $today ."</p>";

            if($today < $row[2])
            {
                $chargeDays += diffDates($row[1],  $today);
            }
            else
            {
                $chargeDays += diffDates($row[1],  $row[2]);
            }
            echo "<p> Days Charged: " . $chargeDays ."</p>";
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
            $endCurDate = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + 365 day"));
            echo "<p>New end date ". $endCurDate . "</p>";
            $resultChildUpdate = mysqli_query($con, "UPDATE RegistrationSheet "
                . "                                  SET StartDate = '$today', EndDate = '$endCurDate' "
                . "                                  WHERE MedicareNum = '$row[0]'");
            if(!$resultChildUpdate)
            {
                print_r(mysqli_error($resultChildUpdate));
            }
        }
        cleanDatabaseBuffer($con);
        echo "</table>";
        if($chargeDays > 0)
        {
           echo "<p>Amount Charged: $". $chargeDays * 7.00 ."</p>";
            $chargeDays = $chargeDays * 7.00;

            $resultInvoices = mysqli_query($con, "SELECT * FROM Invoices WHERE FamilyID = '$familyId'");
            if(!$resultInvoices)
            {
                print_r(mysqli_error($con));
            }
            if(mysqli_num_rows($resultInvoices) == NULL)
            {
                echo "Family doesn't have an invoice! <p> Creating one now </p>";
                $resultInvoice = mysqli_query($con, "INSERT INTO Invoices VALUES('$familyId', '$chargeDays', NULL, NULL, NULL);");
            }
            else
            {
                $resultInvoice = mysqli_query($con, "UPDATE Invoices "
                        . "                          SET Balance = Balance + '$chargeDays' "
                        . "                          WHERE FamilyID = '$familyId';");
            }

            if(!$resultInvoice)
            {
                print_r(mysqli_error($resultInvoice));
            } 
        }
        else
        {
            echo "<p> No days to charge</p>";
        }
        
        $resultInvoices = mysqli_query($con, "SELECT * FROM Invoices WHERE FamilyID = '$familyId'");
        if(!$resultInvoices)
        {
            print_r(mysqli_error($con));
        }
        if(mysqli_num_rows($resultInvoices) != 0)
        {
            echo "
            <h3>Family Invoice:</h3>
            <table Border='1'>
            <tr>
            <th>Family ID</th>
            <th>Balance</th>
            <th>Recurring Credit Card Payment</th>
            </tr>";
            
            while($row = mysqli_fetch_array($resultInvoices, MYSQL_BOTH))
            {
                $ccNum = $row[3];
                $expDate = $row[2];
                $autoPay = $row[4];
                echo "<tr>
                <td>" . $row[0] . "</td>
                <td>" . $row[1] . "</td>
                <td>" . $row[4] . "</td>
                </tr>";
            }
            cleanDatabaseBuffer($con);
            echo "</table>";
            if(isset($_POST['submitPaymentInfo']) AND ($ccNum != NULL AND $expDate != NULL AND $autoPay != NULL))
            {
                $resultPayment = mysqli_query($con, "UPDATE Invoices "
                        . "                          SET Balance = 0, ExpDate = '$expDate', CreditCardNum = '$ccNum', PreAuthorized = '$autoPay'"
                        . "                          WHERE FamilyID = '$familyId'");
                if(!$resultPayment)
                {
                    print_r(mysqli_error($con));
                }
                echo "<meta http-equiv='refresh' content='0'>";
            }
            else if(isset($_POST['submitPaymentInfo']))
            {
                $field = array('ExpDate', 'CCNum', 'Type');
                foreach($field as $index)
                {
                    if (empty($_POST[$index])) 
                    {
                        die("Empty Text Field: ". $index . "Push back button");
                    }
                }
                $expDate = $_POST['ExpDate'];
                $ccNum = md5($_POST['CCNum']);
                $autoPay = $_POST['Type'];
                $resultPayment = mysqli_query($con, "UPDATE Invoices "
                        . "                          SET Balance = 0, ExpDate = '$expDate', CreditCardNum = '$ccNum', PreAuthorized = '$autoPay'"
                        . "                          WHERE FamilyID = '$familyId'");
                if(!$resultPayment)
                {
                    print_r(mysqli_error($con));
                }
                echo "<meta http-equiv='refresh' content='0'>";
            }
            else if(isset($_POST['submitPaymentInfo']))
            {
                echo "<p> Payment Info Missing. Payment Not Processed.";
                die();
            }
        
            ?>
            <form id="PaymentInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <br />
                    <?php
                    if($ccNum == NULL || $expDate == NULL || $autoPay == NULL)
                    { ?>
                        Credit Card Number: <input name="CCNum" type="text" id="CCNum" />
                        <br />
                        Expiration Date(yy-mm): <input name="ExpDate" type="text" id="ExpDate" />
                        <br />
                        Pre-Authorized Payment?  <select name="Type">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                            </select><?php
                    }?>
                    <input name="submitPaymentInfo" type="submit" value="Submit Payment" />
            </form><?php
        }
       
}
else 
{
        ?>
        <form id="FamilyInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <br />
                Family Name: <input name="LastName" type="text" id="LastName" />
                <br />
                Phone Number: <input name="PhoneNum" type="text" id="PhoneNum" />
                <br />
                <input name="submitFamilyInfo" type="submit" value="Submit" />
        </form>
        <?php 
}

?>
        <FORM METHOD="LINK" ACTION="resetPayment.php">
        <INPUT NAME= "Reset" TYPE="submit" VALUE="Reset Search">
        </FORM>
        <FORM METHOD="POST" ACTION="">
        <INPUT NAME= "returnEmployee" TYPE="submit" VALUE="Return to Menu">
        </FORM>
	</body>
</html>