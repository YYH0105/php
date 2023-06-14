<!DOCTYPE html>
<html>
<head>
    <title>電影詳情</title>
    <style>
        body {
            background-image: url('https://photo69.mac89.com/EPS180504/EPS180504_9/wUnyrdH1k2_small.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
        }

        .left-content {
            flex: 1;
            padding-right: 20px;
        }

        .right-content {
            flex: 1;
            padding-left: 20px;
            border-left: 1px solid #ccc;
        }

        h2 {
            text-align: center;
            margin-top: 0;
        }

        img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        p {
            margin: 0;
        }

        .comments-section {
            margin-top: 40px;
        }

        h3 {
            margin-top: 0;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .comment {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .like-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .like-button.liked {
            background-color: #f44336;
        }

        .like-count {
            margin-top: 10px;
            font-weight: bold;
        }

        .back-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-content">
            <a href="index.php" class="back-button">回首頁</a>

            <?php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "php8";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("連接資料庫失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8");

if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];

    $sql = "UPDATE movie SET views = views + 1 WHERE movie_id = $movie_id";
    $conn->query($sql);

    $sql = "SELECT * FROM movie WHERE movie_id = $movie_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
        echo '<h2>' . $movie['name'] . '</h2>';
        echo '<img src="' . $movie['image'] . '" alt="' . $movie['name'] . '" width="295" height="400">';

        $sql = "SELECT SUM(number) AS total_likes FROM likenumber WHERE movie_id = '$movie_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_likes = $row['total_likes'];

        $username = $_SESSION['username'];

        $sql = "SELECT * FROM likenumber WHERE movie_id = '$movie_id' AND username = '$username'";
        $result = $conn->query($sql);
        $is_liked = $result->num_rows > 0;

        if (isset($_POST['like'])) {
            $movie_id = $_POST['movie_id'];
            $username = $_SESSION['username'];

            $sql = "SELECT * FROM likenumber WHERE movie_id = '$movie_id' AND username = '$username'";
            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                $sql = "INSERT INTO likenumber (movie_id, username, number) VALUES ('$movie_id', '$username', 1)";
                $conn->query($sql);
            } else {
                $sql = "DELETE FROM likenumber WHERE movie_id = '$movie_id' AND username = '$username'";
                $conn->query($sql);
            }

            header("Location: " . $_SERVER['PHP_SELF'] . "?movie_id=" . $movie_id);
            exit();
        }

        echo '<form id="like-form" method="POST" action="' . $_SERVER['PHP_SELF'] . '?movie_id=' . $movie_id . '">';
        echo '<input type="hidden" name="movie_id" value="' . $movie_id . '">';
        echo '<button type="submit" name="like" class="like-button' . ($is_liked ? ' liked' : '') . '">' . ($is_liked ? '已收藏' : '收藏') . '</button>';
        echo '<div class="like-count">' . $total_likes . '人收藏</div>';
        echo '<p>瀏覽量：' . $movie['views'] . '</p>';
        echo '</form>';

        echo '<p>類型：' . $movie['type'] . '</p>';
        echo '<p>年分：' . $movie['year'] . '</p>';
        echo '<p>大綱：' . $movie['story_Line'] . '</p>';

        $director_id = $movie['director_id'];
        $sql = "SELECT * FROM director WHERE director_id = $director_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $director = $result->fetch_assoc();
            echo '<p>導演：<a href="director.php?director_id=' . $director['director_id'] . '">' . $director['name'] . '</a></p>';
        }

        $sql = "SELECT actors.actor_id, actors.name FROM actors INNER JOIN movies_actors ON actors.actor_id = movies_actors.actor_id WHERE movies_actors.movie_id = $movie_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<p>主要演員：';
            while ($actor = $result->fetch_assoc()) {
                echo '<a href="actor.php?actor_id=' . $actor['actor_id'] . '">' . $actor['name'] . '</a> ';
            }
            echo '</p>';
        }
    } else {
        echo '找不到該電影的詳情。';
    }
} else {
    echo '錯誤：缺少電影編號。';
}

$conn->close();
?>
        </div>

        <div class="right-content">
            <div class="comments-section">
                <form id="comment-form" method="POST" action="add_comment.php">
                    <label for="comment">評論：</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                    <br>
                    <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                    <button type="submit">發表評論</button>
                </form>

                <?php
                $conn = new mysqli($servername, $db_username, $db_password, $dbname);

                if ($conn->connect_error) {
                    die("連接資料庫失敗：" . $conn->connect_error);
                }

                $conn->set_charset("utf8");

                $sql = "SELECT * FROM comments WHERE movie_id = '$movie_id'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $username = $row['username'];
                        $comment = $row['comment'];

                        echo '<div class="comment"><strong>' . $username . '：</strong>' . $comment . '</div>';
                    }
                } else {
                    echo '尚無評論';
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>