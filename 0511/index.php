<meta charset="utf-8">
<?php
$link=mysqli_connect('localhost','root','','school');

if(!mysqli_select_db($link,'school')) {
    die("無法開啟資料庫!<br/>");
}else{
    echo "資料庫:開啟成功!<br/>";
}
mysqli_set_charset($link, "utf8");
$SQL='SELECT * FROM student';

if($result=mysqli_query($link, $SQL)) {
    echo "<a href='add.php'>新增資料</a>";
    echo "<table border='1'>";
    while($row=mysqli_fetch_assoc($result)){
        echo "<tr/>";
        echo "<td>".$row['No']."</td><td>".$row['Name']."</td><td>".$row['Id']."</td><td>".$row['Department']."</td><td><a href='update.php?No=".$row['No']."'>修改</a></td><td><a href='delete.php?No=".$row['No']."'>刪除</a></td>";
        echo "</tr>";
    }
    echo "</table>";

}else{
    echo "資料庫查詢失敗";
}




mysqli_close($link);//資料庫關閉

?>