<?php
session_start();
unset($_SESSION['LastName']);
unset($_SESSION['PhoneNum']);
header('Location: FamilyInfo.php' );
   die();
?>