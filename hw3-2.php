<meta charset="utf-8">

<html>
<head>
<title> 填寫完成 </title>
</head>

<?php

  $join=$_POST["join"];
  if($join=="yes"){
     echo "成功報名<br/>";
  }else{
     echo "已拒絕參加<br/>";
  }

  $name=$_POST["name"];
  echo "您的姓名是:".$name."<br/>";

  $id=$_POST["id"];
  echo "您的學號是:".$id."<br/>";
  
echo"<br>";
echo"<br>";

?>

</body>
</html>
