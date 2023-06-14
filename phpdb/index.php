<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login2.php");
    exit;
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: login2.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>電影評論網頁</title>
    <style>
        @keyframes title-animation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        h1 {
            text-align: center;
            margin-top: 40px;
            font-size: 48px;
            font-weight: bold;
            color: #fbf7f7;
            animation: title-animation 2s infinite;
        }

        body {
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3)), url("back.jpg");
            background-size: cover;
            background-position: center;
        }

        .user-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
        }

        .user-area .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
        }

        .user-area .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-area .user-name {
            color: #ffffff;
            font-weight: bold;
            font-size: 16px;
        }

        .movie-image {
            margin: 15px;
            width: 210px;
            height: 290px;
            transition: transform 0.1s;
        }

        .movie-image:hover {
            transform: scale(1.2);
        }

        .personal-page-button {
            text-align: center;
            margin-top: 20px;
        }

        .personal-page-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffffff;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-right: 10px;
        }

        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffffff;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        
</style>
</head>
<body>
    <center>
        <h1>Movie Comment</h1>
        <br/>
    </center>
    <div class="user-area">
    <div style="margin-left: auto;">
        <?php if(isset($_SESSION["role"]) && $_SESSION["role"] === "admin"){?>
            <a href="admin.php" class="personal-page-button">管理者頁面</a>
        <?php } else { ?>
            <a href="person.php" class="personal-page-button">個人主頁</a>
        <?php } ?>
        <a href="?logout=1" class="logout-button">登出</a>
    </div>
</div>
    <hr/>
    
    <?php
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "php8";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("連接失敗：" . $conn->connect_error);
        }

        $conn->set_charset("utf8");

        $sql = "SELECT * FROM movie ORDER BY movie_id ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "&emsp;";
                echo '<a href="movie.php?movie_id=' . $row['movie_id'] . '">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '" class="movie-image">';
                echo '</a>';
            }
        } else {
            echo "目前沒有上映的電影。";
        }

        $conn->close();
    ?>
</body>
</html>