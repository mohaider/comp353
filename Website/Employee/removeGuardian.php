<?php
session_start();
?>
<html>
    <head>
		<title>Remove Guardian Information</title>
    </head>
    <body><H3>Remove Guardian (There must be at least one!)</h3>
<?php
        include_once("../scripts/db_script.php");
        if ((isset($_SESSION['familyID'])))
	{
                if (empty($_SESSION['familyID'])) 
                {
                    die("Page missing family data from FamilyInfo.php");
                }
                  
                $con = db_connect();
                $familyId = $_SESSION['familyID'];
                $resultGuardians = mysqli_query($con, "SELECT *\n"
                                                        . "FROM Guardian\n"
                                                        . "JOIN PrimaryCaretaker AS pc\n"
                                                        . "ON ID = pc.GuardianID\n"
                                                        . "WHERE pc.FamilyID = '$familyId'");
                if(!$resultGuardians)
                {
                    print_r(mysqli_error($con));
                }
                echo "
                <h3>Family Guardians:</h3>
                <table Border='1'>
                <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Individual Phone Number</th>
                <th>Guardian Type</th>
                </tr>";
                $idArray = array();
                while($row = mysqli_fetch_array($resultGuardians, MYSQL_BOTH))
                {
                    $idArray[] = $row[0];
                    echo "<tr>
                    <td>" . $row[0] . "</td>
                    <td>" . $row[1] . "</td>
                    <td>" . $row[2] . "</td>
                    <td>" . $row[3] . "</td>
                    <td>" . $row[4] . "</td>
                    </tr>";
                }
                cleanDatabaseBuffer($con);
                echo "</table>";
                mysqli_free_result($resultGuardians);
                
                if(isset($_POST['removeGuardianInfo']))
                {
                    $edit = $_POST['editGuardian'];
                    $resultDelete = mysqli_query($con, "DELETE FROM PrimaryCaretaker WHERE GuardianID = '$edit';");
                    if($resultDelete)
                    {
                        echo "Change Successful";
                        cleanDatabaseBuffer($con);
                        $resultDelete = mysqli_query($con, "DELETE FROM Guardian WHERE ID = '$edit';");
                    }
                    else
                    {
                         printf("Errormessage: %s\n", mysqli_error($con));
                    }
                }
                mysqli_close($con);
        }

?>
<form id="GuardianInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<?php
echo "Choose Guardian To Edit: <select name='editGuardian'>";
foreach ($idArray as $value) {
      echo ""
    . "<option value='$value'>'$value'</option>";
}
echo "</select>";
?>
    <br/>
<input name="removeGuardianInfo" type="submit" value="Remove" />
</form>
<FORM METHOD="POST" ACTION="FamilyInfo.php">
<INPUT NAME= "returnFamily" TYPE="submit" VALUE="Return to Information">
</FORM>