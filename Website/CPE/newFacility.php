<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['FacilityManagement'])) {
    header('Location:facilityManagement.php');
    die();
}

$typeHome = "Home";
$typeCenter = "Center";
global $typeHome;
global $typeCenter;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style>
            .error {color: #FF0000;}
        </style>
        <title>Facility Creation</title>

    </head>
    <body>
        <?php
        include_once("../scripts/db_script.php");
        $con = db_connect();
        
        ?>
        <?php
// define variables and set to empty values
$typeErr = $addressErr = $phoneNumErr = "";
$daycareType = $address = $phoneNum =  "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
   $daycareType = test_input($_POST["centertype"]);
   if (empty($_POST["address"]))
     {$addressErr = "Address is required";}
   else
     {
     $address = test_input($_POST["address"]);
     }
     

   if (empty($_POST["phoneNum"]))
     {$phoneNumErr = "Gender is required";}
   else
     {$phoneNum = test_input($_POST["phoneNum"]);}
}

function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}

function writeToDatabase($data)
{
    
}
?>

        
<h2>New Facility Creation</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
    Facility Type: <select name="centertype">
        <option value="Home">Home</option>
         <option value="Center">Daycare Center</option>
    </select>
   <br><br>
   Address: <input type="text" name="address" value="<?php echo $address;?>">
   <span class="error">* <?php echo $addressErr;?></span>
   <br><br>
   Phone Number:
   <input type="text" name  ='phoneNum' value ="<?php echo $phoneNum;?>">
       <span class="error">* <?php echo $phoneNumErr;?></span>
   <br><br>
   <input type="submit" name="submit" value="Submit"> 
</form>



<?php
//todo remove this  
echo "<h2>Your Input:</h2>";
echo $daycareType;
echo "<br>";
echo $address;
echo "<br>";
echo $phoneNum;
?>

        <FORM METHOD="POST" ACTION=''>
            <INPUT NAME= "FacilityManagement" TYPE="submit" VALUE="Return to Facility Management">
        </FORM>
    </body>
</html>