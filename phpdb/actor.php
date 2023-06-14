<!DOCTYPE html>
<html>
<head>
    <title>演員詳情</title>
    <style>
        body {
            background-image: url('https://photo69.mac89.com/EPS180504/EPS180504_9/wUnyrdH1k2_small.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
    }

    .left-content {
        flex: 1;
        padding-right: 20px;
    }

    .right-content {
        flex: 1;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    img {
        width: 200px;
        height: 300px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    p {
        font-size: 18px;
        margin-bottom: 10px;
    }

    ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    ul li {
        margin-bottom: 10px;
    }

    ul li img {
        width: 295px;
        height: 400px;
        object-fit: cover;
        border-radius: 5px;
    }
</style>


</head>
<center>
<body>
<div class="container">
        <div class="left-content">
    <?php
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "php8";
    
            // 建立連接
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);
            // 確保從 URL 中獲取了 actor_id
            if (isset($_GET['actor_id'])) {
                $actor_id = $_GET['actor_id'];
    
                // 查詢指定演員的資訊
                $actor_query = "SELECT * FROM actors WHERE actor_id = $actor_id";
                $actor_result = $conn->query($actor_query);
    
                if ($actor_result->num_rows > 0) {
                    $actor = $actor_result->fetch_assoc();
                    $actor_name = $actor['name'];
                    $actor_image = $actor['image'];
                    $actor_age = $actor['age'];
    
                    // 查詢演員的代表作品
                    $movies_query = "SELECT movie.* FROM movie 
                                    INNER JOIN movies_actors ON movie.movie_id = movies_actors.movie_id 
                                    WHERE movies_actors.actor_id = $actor_id";
                    $movies_result = $conn->query($movies_query);
    
                    $movies = [];
                    if ($movies_result->num_rows > 0) {
                        while ($movie = $movies_result->fetch_assoc()) {
                            $movies[] = $movie;
                        }
                    }
    
                    // 顯示演員詳細資訊
                    echo '<h1>' . $actor_name . '</h1>';
                    echo '<img src="' . $actor_image . '" alt="演員圖片">';
                    echo '<p>生日：' . $actor_age . '</p>';
                } else {
                    echo '<p>找不到指定的演員</p>';
                }
            } else {
                echo '<p>缺少演員識別符號</p>';
            }
            ?>
        </div>
    
        <div class="right-content">
            <?php
            if (!empty($movies)) {
                echo '<h2>代表作品：</h2>';
                foreach ($movies as $movie) {
                    $movie_id = $movie['movie_id'];
                    $movie_name = $movie['name'];
                    $movie_image = $movie['image'];
                    echo '<a href="movie.php?movie_id=' . $movie_id . '">';
                    echo '<img src="' . $movie_image . '" alt="電影圖片" class="movie-image">';
                    echo '</a>';
                }
            } else {
                echo '<p>暫無代表作品</p>';
            }
            ?>
        </div>
    </div>
    </body>
</html>    