<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: login.php");
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

$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["username"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$commentSQL = "SELECT comments.comment, comments.movie_id, user.username, movie.name
        FROM comments 
        INNER JOIN user ON comments.username = user.username
        INNER JOIN movie ON comments.movie_id = movie.movie_id";
$commentResult = $conn->query($commentSQL);
?>

<!DOCTYPE html>
<html>
<head>
    <title>個人頁面</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .title {
            padding: 1rem 2rem;
            color: #fff;
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
            background-image:url("b.png");
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            color:white;
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
        width: 270px;
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

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .button {
            padding: 10px 20px;
            background-color: #049;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #003270;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="button-container">
            <a href="index.php" class="button">回到首頁</a>
            <a href="login2.php?logout=true" class="button">登出</a>
        </div>
    </div>
    <h1>管理者頁面</h1>
    <?php
    if ($result->num_rows > 0) {
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
        echo '<tr><th>使用者名稱</th><th>電影名稱</th><th>留言</th></tr>';

        while ($comment = $commentResult->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$comment['username'].'</td>';
            echo '<td>'.$comment['name'].'</td>';
            echo '<td>'.$comment['comment'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    } else {
        echo '沒有過去的留言。';
    }
    echo '</div>';
    ?>
<div class="container">
    <div class="title">留言數量比率</div>
    <div class="chart-container">
        <canvas id="comment-chart"></canvas>
    </div>
</div>

<script>
    <?php
    $movieSQL = "SELECT movie.name, COUNT(comments.movie_id) as comment_count
                FROM movie 
                LEFT JOIN comments ON movie.movie_id = comments.movie_id
                GROUP BY movie.movie_id
                ORDER BY comment_count DESC
                LIMIT 10";
    $movieResult = $conn->query($movieSQL);

    $movieData = [];
    $commentCounts = [];

    while ($movie = $movieResult->fetch_assoc()) {
        $movieData[] = $movie['name'];
        $commentCounts[] = $movie['comment_count'];
    }
    ?>

    var movieData = <?php echo json_encode($movieData); ?>;
    var commentCounts = <?php echo json_encode($commentCounts); ?>;

    var ctx = document.getElementById('comment-chart').getContext('2d');
    var commentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: movieData,
            datasets: [{
                label: '留言數量',
                data: commentCounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>

<div class="container">
    <div class="title">電影收藏比例</div>
    <div class="chart-container">
        <canvas id="like-chart"></canvas>
    </div>
</div>

<script>
    <?php
$likeSQL = "SELECT movie.name, COUNT(likenumber.movie_id) as like_count
FROM movie 
LEFT JOIN likenumber ON movie.movie_id = likenumber.movie_id
GROUP BY movie.movie_id
ORDER BY like_count DESC
LIMIT 10";
    $likeResult = $conn->query($likeSQL);

    $movieData = [];
    $likeCounts = [];

    while ($like = $likeResult->fetch_assoc()) {
        $movieData[] = $like['name'];
        $likeCounts[] = $like['like_count'];
    }
    ?> 

    var movieData = <?php echo json_encode($movieData); ?>;
    var likeCounts = <?php echo json_encode($likeCounts); ?>;

    var ctx = document.getElementById('like-chart').getContext('2d');
    var likeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: movieData,
            datasets: [{
                label: '收藏數量',
                data: likeCounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>

<?php
$movieSQL = "SELECT name, views FROM movie";
$movieResult = $conn->query($movieSQL);

$movieData = [];
$viewsData = [];

while ($movie = $movieResult->fetch_assoc()) {
    $movieData[] = $movie['name'];
    $viewsData[] = $movie['views'];
}
?>

<div class="container">
    <div class="title">瀏覽次數分析</div>
    <div class="chart-container">
        <canvas id="views-chart"></canvas>
    </div>
</div>

<script>
    <?php
    $viewsSQL = "SELECT movie.name, movie.views
                FROM movie
                ORDER BY movie.views DESC
                LIMIT 10";
    $viewsResult = $conn->query($viewsSQL);

    $movieData = [];
    $viewCounts = [];

    while ($views = $viewsResult->fetch_assoc()) {
        $movieData[] = $views['name'];
        $viewCounts[] = $views['views'];
    }
    ?>

    var movieData = <?php echo json_encode($movieData); ?>;
    var viewCounts = <?php echo json_encode($viewCounts); ?>;

    var ctx = document.getElementById('views-chart').getContext('2d');
    var viewsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: movieData,
            datasets: [{
                label: '瀏覽次數',
                data: viewCounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],

                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>
</body>
</html>