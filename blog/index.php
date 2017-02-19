<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="./css/nav.css">
        <link rel="stylesheet" href="./css/input.css">
        <link rel="stylesheet" href="./css/body.css">
        <link rel="stylesheet" href="./css/header.css">
        <link rel="stylesheet" href="./css/footer.css">
        <title>N0rR's blog</title>
        <?php
        $host = 'localhost';
        $user = 'root';
        $password = '****';
        $dbname = 'n0rr_blog';

        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            //공용 IP 확인
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            // 프록시 사용하는지 확인
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $passsql = "insert into visits_non_member values(inet_aton('".$ip."'), sysdate());";

        $conn = new mysqli($host, $user, $password, $dbname);

        session_start();
        if (!($conn->connect_error)) {
            if(!($_SESSION['is_pass'] == true) && !($_SESSION['is_login'] == true)) {
                $conn->query($passsql);
                $_SESSION['is_pass'] = true;
            }
        } else {
            die("Connection failed: " . $conn->connect_error);
        }
        ?>
    </head>
    <body>
        <header>
            <div class="profile">
                <img class="profile_image" src="./background/profile.jpg">
                <div class="profile_name">
                    N0rR
                </div>
            </div>
        </header>
        <nav>
            <p>N0rR's blog</p>
            <div class="account">
                <?php
                if($_SESSION['is_login'] == true) {
                    echo $_SESSION['nickname'];
                    if($_SESSION['nickname'] === 'admin') {
                        echo '<div class="button" onclick="location.href=\'writeform.html\'">Write Post</div>';
                    }
                    echo '<div class="button" onclick="location.href=\'signout.php\'">Sign out</div>';
                } else {
                    echo '<div class="button" onclick="location.href=\'loginform.html\'">Sign in / Sign up</div>';
                }
                ?>
            </div>
        </nav>
        <div class="center">
            <?php
            if (!($conn->connect_error)) {
                $selectsql = "select preview, title, category, createtime, visits from posts;";
                $result = $conn->query($selectsql);
                if($result->num_rows != 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="./view.php?t='.urlencode($row['title']).'">';
                        echo '<div class="content">';
                        echo '<img src="'.$row['preview'].'">';
                        echo '<div class="contentinfo">';
                        echo '<h2>'.$row['title'].'</h2><br>';
                        echo '<h4>'.$row['category'].'</h4> | <h4>'.$row['createtime'].'</h4> | <h4>'.$row['visits'].' view</h4>';
                        echo '</div></div></a>';
                    }
                }
            } else {
                die("Connection failed: " . $conn->connect_error);
            }
            ?>
        </div>
        <footer>
            <h1>Designed by N0rR.</h1><br>
            <a href="http://fb.com">FaceBook</a> | <a href="http://github.com">GitHub</a>
        </footer>
    </body>
</html>