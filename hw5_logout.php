<?php
session_start();

session_destroy();
header("location: hw5_form.php"); 
?>