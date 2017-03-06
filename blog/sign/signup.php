<?php
$host = 'localhost';
$user = 'root';
$password = '****';
$dbname = 'n0rr_blog';

$id = $_POST['id'];
$nickname = $_POST['nickname'];
$pw = $_POST['pw'];
$re_pw = $_POST['re_pw'];
$email = $_POST['email'];

$id = str_replace('\'', '\\\'', $id);
$id = str_replace('\"', '\\\"', $id);
$nickname = str_replace('\'', '\\\'', $nickname);
$nickname = str_replace('\"', '\\\"', $nickname);
$pw = str_replace('\'', '\\\'', $pw);
$pw = str_replace('\"', '\\\"', $pw);
$email = str_replace('\'', '\\\'', $email);
$email = str_replace('\"', '\\\"', $email);

$checksql = "select * from account where id='".$id."' or nickname='".$nickname."';";
$insertsql = "insert into account values('".$id."', password('".$pw."'), '".$nickname."', '".$email."', sysdate());";

$conn = new mysqli($host, $user, $password, $dbname);

if (!($conn->connect_error)) {
	if(strlen($id) >= 4 && strlen($pw) >= 4 && strlen($nickname) && strlen($email) && strlen($id) <= 15 && strlen($pw) <= 15 && strlen($nickname) <= 50) {
		$result = $conn->query($checksql);
		if($result->num_rows == 0) {
			if($pw === $re_pw) {
				$conn->query($insertsql);
				$conn->close();
				echo '<script>alert(\'계정 생성을 정상적으로 완료했습니다.\');location.href=\'./\';</script>';
			} else {
				$conn->close();
				echo '<script>alert(\'패스워드가 일치하지 않습니다.\');history.back();</script>';
			}
		} else {
			$conn->close();
			echo '<script>alert(\'입력한 아이디나 닉네임이 이미 존재합니다.\');history.back();</script>';
		}
	} else {
		$conn->close();
		echo '<script>alert(\'양식을 모두 맞춰주세요.\');history.back();</script>';
	}
} else {
	die("Connection failed: " . $conn->connect_error);
}
?>