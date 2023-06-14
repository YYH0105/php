<meta charset="utf-8">
<html>
<?php
header("Pragma: public");
header("Expires: 0");
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: inline; filename="applicant.xls";');
header('Content-Transfer-Encoding: binary');


echo "<table border='1'>";
echo "<tr>";
echo "<td>No.</td>";
echo "<td>Name</td>";
echo "<td>ID</td>";
echo "<td>Department</td>";
echo "</tr>";

$link=mysqli_connect('localhost','root','','school');

mysqli_set_charset($link, "utf8");
$SQL='SELECT * FROM student';

if($result=mysqli_query($link, $SQL)) {
    while($row=mysqli_fetch_assoc($result)){
        echo "<tr/>";
        echo "<td>".$row['No']."</td>";
        echo "<td>".$row['Name']."</td>";
        echo "<td>".$row['Id']."</td>";
        echo "<td>".$row['Department']."</td>";
        echo "</tr>";
    }  
}else{
    echo "資料庫查詢失敗";
}
echo "</table>";

mysqli_close($link);

?>

</html>