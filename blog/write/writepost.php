<?php
$host = 'localhost';
$user = 'root';
$password = '****';
$dbname = 'n0rr_blog';

$title = $_POST['title'];
$category = $_POST['category'];
$uploaddir = '../image/';
$content = $_POST['content'];
session_start();
$nickname  = $_SESSION['nickname'];
$uploadfile = $uploaddir.basename($_FILES['preview']['name']);
$content = str_replace("\n", "<br>", $content);
$insertsql = "insert into posts values('".urlencode($title)."', '".urlencode($category)."', '".urlencode($uploadfile)."', '".urlencode($content)."', sysdate(), 0);";
$passsql = "select title from posts where title='".urlencode($title)."';";
if($nickname == 'admin') {
	if(move_uploaded_file($_FILES['preview']['tmp_name'], $uploadfile)) {
		if($title != "" && $category != "") {
			$conn = new mysqli($host, $user, $password, $dbname);
			if (!($conn->connect_error)) {
				$result = $conn->query($passsql);
				if($result->num_rows == 0) {
					$conn->query($insertsql);
					echo "<script>alert('정상적으로 업로드되었습니다.');location.href='../view/?t=".urlencode($title)."';</script>";
					$conn->close();
				} else {
					echo '<script>alert(\'같은 제목의 포스트가 존재합니다.\');history.back();</script>';
				}
			} else {
				die("Connection failed: " . $conn->connect_error);
			}
		} else {
			echo '<script>alert(\'제목, 카테고리를 입력해주세요.\');history.back();</script>';
		}
	} else {
		echo '<script>alert(\'알 수 없는 오류로 인해 정상적으로 업로드하지 못했습니다.\');history.back();</script>';
	}
} else {
	echo '<script>alert(\'잘못 된 접근입니다.\');location.href=\'./\';</script>';
}
?>