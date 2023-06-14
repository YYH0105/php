<?php
$No=$_GET['No'];


$link=mysqli_connect('localhost','root','','school');
mysqli_set_charset($link, "utf8");
$SQL="DELETE FROM student WHERE No=$No";

if(mysqli_query($link,$SQL)){
    header("Location:index.php");
}
?>