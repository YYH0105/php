<?php
session_start();

if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "php8";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("連接資料庫失敗: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["username"] = $username;
            if ($username == "Goder") {
                $_SESSION["role"] = "admin";
            } 
            header("Location: index.php");
                exit;
            } else {
                $error = "錯誤的用戶名稱或密碼";
            }
            
        } else {
                $error = "錯誤的用戶名稱或密碼";
        }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>登入</title>
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
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .header {
            color: #aaaaaa;
            margin-bottom: 2000px;
            font-weight: 400;
            text-align: center;
            margin-top: 0;
            position: absolute;
            top: 100px;
            animation: fade-in 1s ease-in-out;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .error-message {
            color: #f44336;
            margin-bottom: 20px;
            text-align: center;
            animation: slide-up 0.5s ease-in-out;
        }

        @keyframes slide-up {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        form {
            width: 100%;
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slide-up 0.5s ease-in-out;
        }

        label {
            color: #ffffff;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 200px;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 3px;
            background-color: #f5f5f5;
            font-size: 14px;
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

        p {
            color: #ffffff;
            margin-top: 15px;
        }

        a {
            display:block;
	        font-weight:bold;
	        color:#FFFFFF;
	        background-color:#98bf21;
	        width:120px;
	        text-align:center;
	        padding:4px;
        }

        a:hover {
            background-color:#7A991A;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1 class="header">Movie Comment</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label>用戶名稱:</label>
        <input type="text" name="username" required><br><br>
        <label>密碼:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="登入">
        <p>還沒有帳號嗎？ <a href="regist2.php">註冊</a></p>
        <?php if (isset($error)) { ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    </form>
</body>
</html>



