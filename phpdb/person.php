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

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "php8";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

$sql = "SELECT * FROM user WHERE username = '" . $_SESSION["username"] . "'";
$result = $conn->query($sql);

$commentSQL = "SELECT comments.comment, comments.movie_id, user.username, movie.name
        FROM comments 
        INNER JOIN user ON comments.username = user.username
        INNER JOIN movie ON comments.movie_id = movie.movie_id
        WHERE user.username = '" . $_SESSION["username"] . "'";
$commentResult = $conn->query($commentSQL);

$likeSQL = "SELECT movie.movie_id, movie.name, movie.image
            FROM movie
            INNER JOIN likenumber ON movie.movie_id = likenumber.movie_id
            WHERE likenumber.username = '" . $_SESSION["username"] . "'";
$likeResult = $conn->query($likeSQL);
?>

<!DOCTYPE html>
<html>
<head>
    <title>個人頁面</title>
    <style>
        body {
            background-image:url("伶牙.jpg");
        }
        .title {
            padding: 1rem 2rem;
            color: #ffffff;
            background: #049;
            -webkit-box-shadow: 5px 5px 0 #003270;
            box-shadow: 5px 5px 0 #003270;
            text-align: center;
            position: relative;
            animation: titleAnimation 1s ease-in-out infinite alternate;
        }

        @keyframes titleAnimation {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-5px);
            }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        h1 {
            color: #ffffff;
            text-align: center;
            margin-top: 30px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .profile-image {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            width: 250px;
            height: 200px;
            border: 3px solid #dddddd;
            border-radius: 0.5px;
        }

        .profile-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .profile-info {
            width: 65%;
        }

        .profile-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-info th {
            width: 30%;
            background-color: #7d7d7d;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .profile-info td {
            width: 70%;
            text-align: left;
            padding: 10px;
            background-color: #f2f2f2;
        }

        .comments {
            border-top: 2px solid #dddddd;
            padding-top: 30px;
            margin-top: 50px;
        }

        .comments table {
            width: 100%;
            border-collapse: collapse;
        }

        .comments th,
        .comments td {
            padding: 10px;
            text-align: left;
        }

        .comments th {
            background-color: #295cdd;
            color: white;
        }

        .comments tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .liked-movies {
    border-top: 2px solid #dddddd;
    padding-top: 30px;
    margin-top: 50px;
}

.liked-movies .movie-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.liked-movies .movie-container .movie-item {
    width: calc(25% - 20px);
    margin-bottom: 20px;
    text-align: center;
}

.liked-movies .movie-container .movie-item .movie-image {
    width: 100%;
    border-radius: 5px;
    cursor: pointer;
}

.liked-movies .movie-container .movie-item .movie-image:hover {
    opacity: 0.8;
}

.button-container .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #049;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            margin-right: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="header">
        <div class="button-container">
        <a href="index.php" class="button">回到首頁</a>    
        </div>
    </div>
    <h1>個人頁面</h1>
    <?php
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        echo '<div class="container">';
        echo '<div class="title">基本資料</div>';
        echo '<div class="profile">';
        echo '<div class="profile-image">';
        echo '<img border="3" src="'.$user['image'].'" alt="'.$user['username'].'" width="200" height="250">';
        echo '</div>';
        echo '<div class="profile-info">';
        echo '<table>';
        echo '<tr><th>名字:</th><td>'.$user['username'].'</td></tr>';
        echo '<tr><th>帳號:</th><td>'.$user['email'].'</td></tr>';
        echo '<tr><th>生日:</th><td>'.$user['birth'].'</td></tr>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '找不到該使用者的詳情。';
    }

    echo '<div class="container">';
    echo '<div class="title">過去留言</div>';
    if ($commentResult->num_rows > 0) {
        echo '<div class="comments">';
        echo '<table>';
        echo '<tr><th>電影名稱</th><th>留言</th></tr>';

        while ($comment = $commentResult->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$comment['name'].'</td>';
            echo '<td>'.$comment['comment'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    } else {
        echo '沒有過去的留言。';
    }

    echo '<div class="liked-movies">';
    echo '<div class="title">收藏作品</div><br/>';
    if ($likeResult->num_rows > 0) {
        echo '<div class="movie-container">';
        while ($like = $likeResult->fetch_assoc()) {
            echo '<div class="movie-item">';
            echo '<img class="movie-image" src="'.$like['image'].'" alt="'.$like['name'].'" width="200" height="250">';
            echo '<div>'.$like['name'].'</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '沒有收藏的作品。';
    }
    echo '</div>';

    echo '</div>';
    ?>
</body>
</html>