<?php
require_once('../calendar/tc_calendar.php');
header ( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
header ("Pragma: no-cache");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Creation Window</title>
<script language="javascript" src="../calendar/calendar.js"></script>
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css">
</head>

<body>
<form id="form1" name="form1" method="post" action="test_calendar_value.php">
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<td>Date :</td>
<td><?php
$myCalendar = new tc_calendar("date5", true, false);
$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
$myCalendar->setPath("../calendar/");
$myCalendar->setYearInterval(2000, 2015);
$myCalendar->dateAllow('2008-05-13', '2010-03-01');
$myCalendar->setDateFormat('j F Y'); 
$myCalendar->writeScript(); 
?></td>
<td><input type="button" name="button" id="button" value="Check the value" onclick="javascript:alert(this.form.date5.value);" /></td>
</tr>
</table>
<p>
<?php
echo("<p>the date value from getDate() at construct time = ".$myCalendar->getDate()."</p>");
?>
<input type="submit" name="Submit" value="Submit" />
</p>
</form>
</body>
</html>