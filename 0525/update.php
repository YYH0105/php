<meta charset='utf-8'>

<form action='updatecheck.php' method='post'>
<?php

$uNo=$_GET['No'];

$link=mysqli_connect('localhost','root','','school');
mysqli_set_charset($link, "utf8");

$SQL="SELECT * FROM student WHERE No=$uNo";
if($result=mysqli_query($link,$SQL)){
    $row=mysqli_fetch_assoc($result);
    $uName=$row['Name'];
    $uId=$row['Id'];
    $uDept=$row['Department'];
}
echo "No:".$uNo."<br>";
echo "<input type='hidden' name='uNo' value='".$uNo."'>";
echo "Name:<input type='text' name='uName' value='".$uName."'><br>";
echo "ID:<input type='text' name='uId' value='".$uId."'><br>";
echo "Department:<input type='text' name='uDept' value='".$uDept."'><br>";



?>
<input type='submit'>
</form>