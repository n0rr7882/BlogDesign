<?php
$host = 'localhost';
$user = 'root';
$password = '****';
$dbname = 'n0rr_blog';

$title = $_POST['title'];
$category = $_POST['category'];
$uploaddir = './image/';
$content = $_POST['content'];
$uploadfile = $uploaddir.basename($_FILES['preview']['name']);

$title = str_replace("'", "\\'", $title);
$title = str_replace('"', '\\"', $title);
$content = str_replace("'", "\\'", $content);
$content = str_replace('"', '\\"', $content);
$uploadfile = str_replace("'", "\\'", $uploadfile);
$uploadfile = str_replace('"', '\\"', $uploadfile);

$insertsql = "insert into posts values('".$title."', '".$category."', '".$uploadfile."', '".$content."', sysdate(), 0);";
if(move_uploaded_file($_FILES['preview']['tmp_name'], $uploadfile)) {
	if($title != "" && $category != "") {
		$conn = new mysqli($host, $user, $password, $dbname);
		if (!($conn->connect_error)) {
			$conn->query($insertsql);
			echo '<script>alert(\'정상적으로 업로드되었습니다.\');location.href=\'./\';</script>';
			$conn->close();
		} else {
			die("Connection failed: " . $conn->connect_error);
		}
	} else {
		echo '<script>alert(\'제목, 카테고리를 입력해주세요.\');history.back();</script>';
	}
} else {
	echo '<script>alert(\'알 수 없는 오류로 인해 정상적으로 업로드하지 못했습니다.\');history.back();</script>';
}
?>