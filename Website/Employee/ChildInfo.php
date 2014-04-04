<?php

?>

<html>
    <head>
		<title>Family Information</title>
    </head>
    <body>
        <H3> Child Information Page</H3>
<?php
include_once("scripts/db_script.php");
if()
{
    
}
else
{
    ?>
    <form id="ChildInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <br />
        Medicare Number: <input name="MediNum" type="text" id="MediNum" />
        <br />
        <input name="submitChildInfo" type="submit" value="Submit" />
     </form>
     <?php
}
?>

    </body>
</html>