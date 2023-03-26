<?php
session_start();

if (($_SESSION["login"]=="pr")||($_SESSION["login"]=="te")||($_SESSION["login"]=="st")) {

}else{
        header("Location: hw5_error.php"); 
    }

?>

<html>

<head>
<meta charset="utf-8">
</head>

<body>
<?php

$prdata=array(1,2,3);
$tedata=array(2,3);
$stdata=array(3);

if ($_SESSION["login"]=="pr"){
    echo "您是校長<br>";
    print_r($prdata); echo"<br>"; print_r($tedata); echo"<br>"; print_r($stdata); echo"<br>";
}
if ($_SESSION["login"]=="te"){
    echo "您是老師<br>";
    print_r($tedata); echo"<br>"; print_r($stdata); echo"<br>";
}
if ($_SESSION["login"]=="st"){
    echo "您是學生<br>";
    print_r($stdata); echo"<br>"; 
}

?>

<a href="hw5_logout.php">登出<a>  
</body>

</html>