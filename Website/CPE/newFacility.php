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
// define variables and set to empty values
$typeErr = $addressErr = $phoneNumErr =$dbError = "";
$daycareType = $address = $phoneNum =  "";
$completeInfo = true;
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
   $daycareType = test_input($_POST["centertype"]);
   if (empty($_POST["address"]))
     {
       $addressErr = "Address is required";
       $completeInfo = false;
       
     }
   else
     {
     $address = test_input($_POST["address"]);
     }
     

   if (empty($_POST["phoneNum"]))
     {
       $phoneNumErr = "Phone# is required";
       $completeInfo = false;
       
     }
   else
     {
       $phoneNum = test_input($_POST["phoneNum"]);
       
     }
     
     if ($completeInfo)
     {
         $args = array("Type" => $daycareType, "Address" => $address, "PhoneNum" => $phoneNum);
        $insertionResults= writeToDatabase($args);
header('Location:facilityManagement.php');
    die();
     }
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
    include_once("../scripts/db_script.php");
        $con = db_connect();
        //mysql query to insert into facility table
        $sql = sprintf(
    'INSERT INTO Facility(%s) VALUES ("%s")',
    implode(',',array_keys($data)),
    implode('","',array_values($data))
);
     $insertionResults = mysqli_query($con, $sql);
   return mysqli_error($con);
}
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
<h2>New Facility Creation</h2>
<span class="error"><?php echo $dbError;?></span>
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



        <FORM METHOD="POST" ACTION=''>
            <INPUT NAME= "FacilityManagement" TYPE="submit" VALUE="Return to Facility Management">
        </FORM>
    </body>
</html>