<!DOCTYPE html>
<html>
<head>
    <title>註冊</title>
    <style>
        body {
            background-image: url("https://pic.52112.com/180801/EPS-180801_11/7PjIFEjcPr_small.jpg");
            background-size: cover;
            font-family: "Arial", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            color: white;
        }

        .container {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .header {
            color: orange;
            margin-bottom: 20px;
            font-weight: 400;
            text-align: center;
            margin-top: 0;
            position: absolute;
            top: 20px;
        }

        .success-message {
  background-color: #00bcd4;
  color: #ffffff;
  padding: 10px;
  border-radius: 3px;
  text-align: center;
  opacity: 0;
  transition: opacity 0.5s ease-in-out;
}

.hidden {
  display: none;
}

        form {
            width: 100%;
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            color: black;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 200px;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 3px;
            background-color: #f5f5f5;
            font-size: 14px;
        }

        input[type="file"] {
            margin-bottom: 15px;
        }

        input[type="submit"] {
            width: 200px;
            padding: 10px;
            background-color: #00bcd4;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            font-size: 14px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #008c9e;
        }

        .login-link {
            color: black;
            margin-top: 15px;
            text-align: center;
        }

        .login-link a {
            color: orangered;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .uploaded-image {
            margin-top: 15px;
            text-align: center;
        }

        .uploaded-image img {
            max-width: 300px;
            max-height: 300px;
        }
    </style>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $insertUsername = $_POST["username"];
    $email = $_POST["email"];
    $insertPassword = $_POST["password"];
    $imageFileName = $_FILES["image"]["name"];
    $birth = $_POST["birthdate"];
    $currentDate = date("Y-m-d");
    if ($birth > $currentDate) {
        echo "生日不能是未來日期";
        exit;
    }

    $hashedPassword = password_hash($insertPassword, PASSWORD_DEFAULT);

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "php8";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("連接資料庫失敗: " . $conn->connect_error);
        }

        $sql = "INSERT INTO user (username, email, password, image, birth) VALUES ('$insertUsername', '$email', '$hashedPassword', '$imageFileName', '$birth')";

        if ($conn->query($sql) === TRUE) {
            echo "註冊成功";
        } else {
            echo "發生錯誤: " . $conn->error;
        }

        $conn->close();

        $targetDirectory = "up/";
        $targetFile = $targetDirectory . $imageFileName;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "圖片上傳失敗。";
            exit;
        }
    }
    ?>
    <div class="container">
        <h2 class="header">註冊</h2>
        <?php if (isset($successMessage)) { ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php } ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
            <label>用戶名稱:</label>
            <input type="text" name="username" required><br><br>
            <label>電子郵件:</label>
            <input type="email" name="email" required><br><br>
            <label>密碼:</label>
            <input type="password" name="password" required><br><br>
            <label>生日:</label>
            <input type="date" name="birthdate" required><br><br>
            <label>圖片:</label>
            <input type="file" name="image" required><br><br>
            <input type="submit" value="註冊">
        </form>
        <p class="login-link">已經有帳號了？<a href="login2.php">登入</a></p>
    </div>
</body>
</html>
