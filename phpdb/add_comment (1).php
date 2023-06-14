<?php
session_start();
$username = $_SESSION['username'];
$comment = $_POST['comment'];
$movie_id = $_POST['movie_id'];

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "php8";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("連接資料庫失敗: " . $conn->connect_error);
}

$sql = "INSERT INTO comments (username, comment, movie_id) VALUES ('$username', '$comment', '$movie_id')";

if ($conn->query($sql) === TRUE) {
    $conn->close();
    header("Location: movie.php?movie_id=$movie_id");
    exit();
} else {
    echo "發生錯誤: " . $conn->error;
}

$conn->close();
?>