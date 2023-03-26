<?php ob_start();

session_start();   

if ($_SESSION["login"]=="No") {

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

?>
    登入失敗!!!
    將在5秒後跳轉至登入頁面<br>
<?php
header("Refresh:5;url=hw5_form.php");  

?>
</body>
</html>

<?php ob_flush(); ?>