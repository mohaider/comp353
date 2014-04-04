<?php
session_start();
unset($_SESSION['MediNum']);
header('Location: ChildInfo.php' );
   die();
?>