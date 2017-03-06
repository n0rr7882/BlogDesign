<?php
$host = 'localhost';
$user = 'root';
$password = '****';
$dbname = 'n0rr_blog';
$conn = new mysqli($host, $user, $password, $dbname);
if(!($conn->connect_error)) {
	session_start();
	$postname = $_POST['postname'];
	if($postname != null) {
		$usernick = $_SESSION['nickname'];
		if($usernick != null) {
			$secret = $_POST['secret'];
			$comment = $_POST['comment'];
			if($secret != null && $comment != "") {
				if(strpos($comment, '<') === false && stripos($comment, '%3C') === false) {
					$insertsql = "insert into comments values('".urlencode($postname)."', '".urlencode($usernick)."', '".urlencode($comment)."', sysdate(), ".$secret.");";
					$conn->query($insertsql);
					echo "<script>location.href='../view/?t=".$postname."';</script>";
				} else {
					echo "<script>alert('XSS 공격 금지.');history.back();</script>";
				}
			} else {
				echo "<script>alert('댓글을 입력해주세요!');history.back();</script>";
			}
		} else {
			echo "<script>alert('로그인해주세요!');location.href='../sign/';</script>";
		}
	}else {
		echo "<script>alert('잘못 된 접근입니다.');history.back();</script>";
	}
} else {
	die("Connection failed: " . $conn->connect_error);
}
?>