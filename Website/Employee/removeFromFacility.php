<?php
session_start();
?>

<html>
    <head>
		<title>Child Information</title>
    </head>
    <body>
        <H3> Remove From Facility Page</H3>
        There will be a $50 fee if the child is removed before the end of the registration term.
        
<?php
include_once("../scripts/db_script.php");
if(isset($_SESSION['MediNum']))
{
    $con = db_connect();
    $mediNum = $_SESSION['MediNum'];
    $empID = $_SESSION['empID'];
    //MYSQL Query
    $resultFacility = mysqli_query($con, "SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empID';");
    if(!$resultFacility)
    {
        print_r(mysqli_error($con));
    }
    $facility = mysqli_fetch_row($resultFacility);
    
    $resultDate = mysqli_query($con, "SELECT StartDate, EndDate"
            . "                       FROM RegistrationSheet"
            . "                       wHERE MedicareNum = '$mediNum'");
    if(!$resultDate)
    {
        print_r(mysqli_error($con));
    }
    $Dates = mysqli_fetch_row($resultDate);
    $today = date("Y-m-d");
    echo "<p>Begin date: " .$Dates[0] . "</p>";
    echo "<p>End dates: " .$Dates[1]. "</p>";
    echo "<p>Today's date: " .$today. "</p>";
    $diffCurrent = abs(strtotime($today) - strtotime($Dates[0]));
    $curYears = floor($diffCurrent / (365*60*60*24));
    
    $diffExp = abs(strtotime($Dates[1]) - strtotime($Dates[0]));
    $expYears = floor($diffExp / (365*60*60*24));
    
    if($curYears < $expYears && isset($_POST['Remove']))
    {
        echo "Registration canceled early. A $50 charge will be added to your invoice.";
        
        $resultFamily = mysqli_query($con, "SELECT DISTINCT(FamilyID) "
                . "                         FROM ChildOf"
                . "                         WHERE MedicareNum = '$mediNum'");
        if(!$resultFamily)
        {
            print_r(mysqli_error($con));
        }
        $family = mysqli_fetch_row($resultFamily);
        
        $resultInvoice = mysqli_query($con, "SELECT *"
                . "                          FROM Invoices"
                . "                          WHERE FamilyID = '$family[0]'");
        if(!$resultInvoice)
        {
            print_r(mysqli_error($con));
        }
        if(mysqli_num_rows($resultInvoice) == 0)
        {
            $resultInvoice = mysqli_query($con, "INSERT INTO Invoices VALUES('$family[0]', '50.00', NULL, NULL, NULL);");
        }
        else
        {
            $resultInvoice = mysqli_query($con, "UPDATE Invoices "
                    . "                          SET Balance = Balance + '50.00' "
                    . "                          WHERE FamilyID = '$family[0]';");
        }
        
        if($resultInvoice)
        {
            echo "<p>$50.00 billed to your invoice.</p>";
            $resultDelete = mysqli_query($con, "DELETE FROM Child WHERE MedicareNum = '$mediNum'");
            if($resultDelete)
            {
                echo "<p>Child Deleted From Facility</p>";
            }
            else 
            {
                print_r(mysqli_error($con));
            }
        }
        else 
        {
            print_r(mysqli_error($con));
        }
    }
}
if(!isset($_POST['Remove']))
{
    ?>
    <form id="ChildInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input name="Remove" type="submit" value="Submit" />
     </form>
        <?php
}
?>
    <FORM METHOD="POST" ACTION="ChildInfo.php">
    <INPUT NAME= "returnChild" TYPE="submit" VALUE="Return to Information">
    </FORM>
    </body>
</html>