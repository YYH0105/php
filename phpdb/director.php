<!DOCTYPE html>
<html>
<head>
    <title>導演詳情</title>
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
<body>
<div class="container">
        <div class="left-content">
    <center>
    <?php
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "php8";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if (isset($_GET['director_id'])) {
        $director_id = $_GET['director_id'];
        $sql = "SELECT * FROM director WHERE director_id = $director_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $director = $result->fetch_assoc();
            echo '<h2>' . $director['name'] . '</h2>';
            echo '<img src="' . $director['image'] . '" alt="' . $director['name'] . '">';
            echo '<p>年齡：' . $director['age'] . '</p>';
        } else {
            echo '找不到該導演的詳情。';
        }
    } else {
        echo '錯誤：缺少導演編號。';
    }
    $conn->close();
    ?>
</div>

<div class="right-content">
    <?php
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if (isset($_GET['director_id'])) {
        $director_id = $_GET['director_id'];
        $sql = "SELECT * FROM movie WHERE director_id = $director_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<h2>代表作品</h2>';
            echo '<ul>';
            while ($movie = $result->fetch_assoc()) {
                echo '<li>';
                echo '<a href="movie.php?movie_id=' . $movie['movie_id'] . '">';
                echo '<img src="' . $movie['image'] . '" alt="' . $movie['name'] . '">';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '找不到導演的代表作品。';
        }
    }
    $conn->close();
    ?>
</div>
</div>
</body>
</html>

