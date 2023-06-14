<meta charset='utf-8'>

<?php
$uNo=$_POST['uNo'];
$uName=$_POST['uName'];
$uId=$_POST['uId'];
$uDept=$_POST['uDept'];

$link=mysqli_connect('localhost','root','','school');
mysqli_set_charset($link, "utf8");

$SQL="UPDATE student SET Name='$uName', Id='$uId', Department='$uDept' WhERE No='$uNo'";
if(mysqli_query($link,$SQL)){
    header("Location:index.php");
}
?>