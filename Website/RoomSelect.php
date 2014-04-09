<?php
session_start();
if($_SESSION['role'] == "Employee")
{
    header('Location: Employee.php');
}

if(isset($_POST['RoomInfo']))
{
    $_SESSION['RoomNum'] = $_POST['RoomNum'];
    header('Location: roominfo.php');
}
?>
<html>
    <head>
        <title>Room Change Information</title>
    </head>
    <body>
        <H3> Room Change Information</H3>
<?php
include_once("scripts/db_script.php");
if(isset($_SESSION['role']))
{
    if($_SESSION['role'] == "Manager")
    {
        $con = db_connect();
        //MYSQL Query
        $empID = $_SESSION['empID'];
        $resultFacility = mysqli_query($con, "SELECT DISTINCT(FacilityID) FROM EmployeeLists WHERE EmpID = '$empID';");
        if(!$resultFacility)
        {
            print_r(mysqli_error($con));
        }
        $facility = mysqli_fetch_row($resultFacility);
        $_SESSION['facilityID'] = $facility[0];

        $resultRoom = mysqli_query($con, "SELECT * "
                . "                       FROM Room"
                . "                       JOIN Houses"
                . "                       ON Room.RoomNum = Houses.RoomNum"
                . "                       WHERE Houses.FacilityID = '$facility[0]'");
        if(!$resultRoom)
        {
            print_r(mysqli_error($con));
        }
        if(mysqli_num_rows($resultRoom) == 0)
        {
            echo "No Rooms!";
        }

        echo "
        <h3>Room List For Facility: ". $facility[0] ."</h3>
        <table Border='1'>
        <tr>
        <th>Room Number</th>
        <th>Age Group</th>
        <th>Extension number</th>
        </tr>";
        $rooms = array();
        while($row = mysqli_fetch_array($resultRoom, MYSQL_BOTH))
        {
            $rooms[] = $row[0];
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        cleanDatabaseBuffer($con);
        echo "</table>"; 
    }
    else if($_SESSION['role'] == "CPE")
    {
        $con = db_connect();
        //MYSQL Query
        $resultRoom = mysqli_query($con, "SELECT * "
                . "                       FROM Room"
                . "                       JOIN Houses"
                . "                       ON Room.RoomNum = Houses.RoomNum");
        if(!$resultRoom)
        {
            print_r(mysqli_error($con));
        }
        if(mysqli_num_rows($resultRoom) == 0)
        {
            echo "No Rooms!";
        }

        echo "
        <h3>Room List For Facility: ". $facility ."</h3>
        <table Border='1'>
        <tr>
        <th>Room Number</th>
        <th>Age Group</th>
        <th>Extension number</th>
        </tr>";
        $rooms = array();
        while($row = mysqli_fetch_array($resultRoom, MYSQL_BOTH))
        {
            $rooms[] = $row[0];
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        cleanDatabaseBuffer($con);
        echo "</table>"; 
    }
}
?>
        
        <form id="RoomInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <?php
        echo "Choose Room: <select name='RoomNum'>";
        foreach ($rooms as $value) {
              echo ""
            . "<option value='$value'>'$value'</option>";
        }
        echo "</select>";
        ?>
        <br />
        <input name="RoomInfo" type="submit" value="Submit" />
        </form>
        <FORM METHOD="POST" ACTION="Manager.php">
        <INPUT NAME= "returnChild" TYPE="submit" VALUE="Return to Information">
        </FORM>
    </body>
</html>