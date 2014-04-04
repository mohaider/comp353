<?php
session_start();
?>
<html>
    <head>
		<title>Edit Family Information</title>
    </head>
    <body><h3>Remove Emergency Contacts</h3>
<?php
        include_once("scripts/db_script.php");
        if ((isset($_SESSION['familyID'])))
	{
                if (empty($_SESSION['familyID'])) 
                {
                    die("Page missing family data from FamilyInfo.php");
                }
                  
                
                $con = db_connect();
                
                $familyId = $_SESSION['familyID'];
                $resultAuthor = mysqli_query($con,   "SELECT *\n"
                                                           . "FROM AuthorizedContact AS ac\n"
                                                           . "JOIN IsAuthorized\n"
                                                           . "ON ac.ContactNumber = IsAuthorized.ContactNumber\n"
                                                           . "WHERE IsAuthorized.FamilyID = '$familyId'");
                if(!$resultAuthor)
                {
                    print_r(mysqli_error($con));
                }
                if(mysqli_num_rows($resultAuthor) == 0)
                {
                    echo "No Emergency Contacts!";
                }
                else
                {
                    echo "
                    <h3>Edit Authorized Contacts For Family:</h3>
                    <table Border='1'>
                    <tr>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Relation Type</th>
                    <th>Contact Incase of Emergency</th>
                    </tr>";
                    $namesArray = array();
                    while($row = mysqli_fetch_array($resultAuthor, MYSQL_BOTH))
                    {
                        $numArray[] = $row[0];
                        echo "<tr>
                        <td>" . $row[1] . "</td>
                        <td>" . $row[0] . "</td>
                        <td>" . $row[2] . "</td>
                        <td>" . $row[3] . "</td>
                        </tr>";
                    }
                    echo "</table>";
                }
                cleanDatabaseBuffer($con);
                mysqli_free_result($resultAuthor);
                 
                if(isset($_POST['removeContactInfo']))
                {
                    $edit = $_POST['editContact'];
                    $resultUpdate = mysqli_query($con, "DELETE FROM AuthorizedContact WHERE ContactNumber = '$edit';");
                    if($resultUpdate)
                    {
                        echo "Change Successful";
                        cleanDatabaseBuffer($con);
                        $resultDelete = mysqli_query($con, "DELETE FROM IsAuthorized WHERE ContactNumber = '$edit';");
                    }
                    else
                    {
                         printf("Errormessage: %s\n", mysqli_error($con));
                    }
                }
                mysqli_close($con);
        }

?>
<form id="ContactInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<?php
 echo "Choose Contact To Edit: <select name='editContact'>";
foreach ($numArray as $value) {
      echo ""
    . "<option value='$value'>'$value'</option>";
}
echo "</select>";
?>
<br />
<input name="removeContactInfo" type="submit" value="Remove" />
</form>
<FORM METHOD="POST" ACTION="FamilyInfo.php">
<INPUT NAME= "returnFamily" TYPE="submit" VALUE="Return to Information">
</FORM>