<?php
session_start();
unset($_SESSION['LastName']);
unset($_SESSION['PhoneNum']);

unset($_SESSION['familyID']);
header('Location: PaymentInfo.php' );
   die();
?>