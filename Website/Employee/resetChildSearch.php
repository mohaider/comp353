<?php
session_start();
unset($_SESSION['MediNum']);
unset($_SESSION['facilityID']);
unset($_SESSION['AgeGroup']);
header('Location: ChildInfo.php' );
   die();
?>