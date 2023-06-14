<?php
$sName=$_POST['sName'];
$sID=$_POST['sID'];
$sDept=$_POST['sDept'];

//echo $sName.$sID.$sDept;

$link=mysqli_connect('localhost','root','','school');
mysqli_set_charset($link, "utf8");
$SQL="INSERT INTO student(Name, Id, Department) VALUES('$sName', '$sID', '$sDept')";

if(mysqli_query($link,$SQL)){
    header("Location:index.php");
}


?>