<?php
session_start();
?>

<html>
    <head>
        <title>Room Change Information</title>
    </head>
    <body>
        <H3> Room Change Information</H3>
<?php
include_once("../scripts/db_script.php");
if(isset($_SESSION['MediNum']))
{
    $mediNum = $_SESSION['MediNum'];
    $con = db_connect();
    //MYSQL Query
    $facility = $_SESSION['facilityID'];
    $age = $_SESSION['AgeGroup'];
    $resultRoom = mysqli_query($con, "SELECT * "
            . "                       FROM Room"
            . "                       JOIN Houses"
            . "                       ON Room.RoomNum = Houses.RoomNum"
            . "                       WHERE Houses.FacilityID = '$facility' AND AgeGroup = '$age'");
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
    if(isset($_POST['RoomInfo']))
    {
        $room = $_POST['RoomNum'];
        $resultUpdate = mysqli_query($con, "UPDATE SeatedInto "
                . "                         SET RoomNum = '$room'"
                . "                         WHERE MedicareNum = '$mediNum' ");

        if($resultUpdate)
        {
            echo "Change Successful";
        }
        else
        {
            print_r(mysqli_error($con));
        }
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
        <FORM METHOD="POST" ACTION="ChildInfo.php">
        <INPUT NAME= "returnChild" TYPE="submit" VALUE="Return to Information">
        </FORM>
    </body>
</html>