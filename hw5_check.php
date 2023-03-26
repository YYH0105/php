<?php
session_start();


$prID="123";  
$prPWD="123";

$teID="456";  
$tePWD="456";

$stID="789";  
$stPWD="789";

$id=$_POST["id"];
$pwd=$_POST["pwd"];

if(($prID==$id)&&($prPWD==$pwd)){
   $_SESSION["login"]="pr";
   header("Location: hw5_ok.php"); 
}elseif(($teID==$id)&&($tePWD==$pwd)){
   $_SESSION["login"]="te";
    header("Location: hw5_ok.php"); 
}elseif(($stID==$id)&&($stPWD==$pwd)){
   $_SESSION["login"]="st";
    header("Location: hw5_ok.php");  
}else{ 
   $_SESSION["login"]="No";
   header("Location: hw5_fail.php");
}