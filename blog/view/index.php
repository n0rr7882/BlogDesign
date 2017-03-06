<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="../css/nav.css">
        <link rel="stylesheet" href="../css/input.css">
        <link rel="stylesheet" href="../css/body.css">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/view.css">
        <title>N0rR's blog</title>
        <?php
        $host = 'localhost';
        $user = 'root';
        $password = '****';
        $dbname = 'n0rr_blog';

        $posttitle = $_GET['t'];
        session_start();
        $usernickname = $_SESSION['nickname'];

        $postsql = "select * from posts where title='".urlencode($posttitle)."';";
        $selectsql = "select * from comments where postname='".urlencode($posttitle)."' order by createtime desc;";
        $conn = new mysqli($host, $user, $password, $dbname);

        if (!($conn->connect_error)) {
	        $result = $conn->query($postsql);
            $comments = $conn->query($selectsql);
	    } else {
	    	die("Connection failed: " . $conn->connect_error);
	    }
        ?>
	</head>
	<body>
        <div class="center">
        	<div class="content">
    		<?php
    		if (!($conn->connect_error)) {
		        if($result->num_rows == 1) {
                	while($row = $result->fetch_assoc()) {
                        $visits = (int)$row['visits'] + 1;
                		echo '<img class="preview" src="'.urldecode($row['preview']).'">';
                		echo '<div class="contentinfo">';
                		echo '<h2>'.urldecode($row['title']).'</h2><br>';
                		echo '<h4>'.urldecode($row['category']).'</h4> | <h4>'.urldecode($row['createtime']).'</h4> | <h4>'.urldecode($row['visits']).' view</h4>';
                		echo '</div>';
                		echo '<div class="write">';
                		echo urldecode($row['content']);
                		echo '</div>';
                	}
                } else {
                	echo '<script>alert(\'잘못 된 접근입니다.\');history.back();</script>';
                }
                $setviewsql = "update posts set visits=".$visits." where title='".urlencode($posttitle)."';";
                $conn->query($setviewsql);
		    } else {
		    	die("Connection failed: " . $conn->connect_error);
		    }
    		?>
        	</div>
        	<div class="content">
        		<div class="contentinfo"> 
                    <h1>댓글</h1>
                    <?php
                    if(!($usernickname == null)) {
                        echo '<form action="./commentupload.php" method="post">
                        <input class="radio" name="secret" type="radio" value="0" checked="checked"> Public
                        <input class="radio" name="secret" type="radio" value="1"> Private
                        <input class="input" name="comment" type="text">
                        <input class="button" value="등록" type="submit">
                        <input type="hidden" name="postname" value="'.$posttitle.'">
                        </form>';
                    } else {
                        echo '<br>댓글을 다시려면 로그인해주세요!';
                    }
                    ?>
        		</div>
                <?php
                if(!($conn->connect_error)) {
                    if($comments->num_rows !== 0) {
                        while($row = $comments->fetch_assoc()) {
                            $rownick = urldecode($row['nickname']);
                            $rowcomm = urldecode($row['comment']);
                            if($row['is_secret'] === '0' || ($usernickname === $rownick || $usernickname === 'admin')) {
                                if($row['is_secret'] === '1') {
                                    echo '<div class="contentinfo" style="color: red;">';
                                } else {
                                    echo '<div class="contentinfo">';
                                }
                                echo $rownick.' | '.$row['createtime'];
                                echo '<br>'.$rowcomm;
                                echo '</div>';
                            }
                        }
                    } else {
                        echo '<div class="contentinfo">';
                        echo '댓글이 없습니다.';
                        echo '</div>';
                    }
                } else {
                    die("Connection failed: " . $conn->connect_error);
                }
                ?>
        	</div>
        </div>
        <footer>
            <h1><a href="../" style="color: white;">Designed by N0rR.</h1><br>
            <a href="http://fb.com">FaceBook</a> | <a href="http://github.com">GitHub</a>
        </footer>
    </body>
    <nav>
        <p><a href="../">N0rR's blog</a></p>
        <div class="account">
            <?php
            if($_SESSION['is_login'] == true) {
                echo $_SESSION['nickname'];
                if($_SESSION['nickname'] === 'admin') {
                    echo '<div class="button" onclick="location.href=\'../write/\'">포스트쓰기</div>';
                }
                echo '<div class="button" onclick="location.href=\'../sign/signout.php\'">로그아웃</div>';
            } else {
                echo '<div class="button" onclick="location.href=\'../sign/\'">로그인 | 계정생성</div>';
            }
            ?>
        </div>
    </nav>
</html>