<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "php8";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("連接資料庫失敗：" . $conn->connect_error);
    }

    $conn->set_charset("utf8");

    $movie_id = $_POST['movie_id'];
    $username = $_SESSION['username'];

    $sql = "SELECT * FROM likenumber WHERE movie_id = '$movie_id' AND username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO likenumber (movie_id, username, number) VALUES ('$movie_id', '$username', 1)";
        if ($conn->query($sql) === true) {
            echo "收藏成功";
        } else {
            echo "收藏失敗：" . $conn->error;
        }
    } else {
        $sql = "DELETE FROM likenumber WHERE movie_id = '$movie_id' AND username = '$username'";
        if ($conn->query($sql) === true) {
            echo "取消收藏成功";
        } else {
            echo "取消收藏失敗：" . $conn->error;
        }
    }

    $conn->close();
}
?>