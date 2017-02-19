<?php
$host = 'localhost';
$user = 'root';
$password = '****';
$dbname = 'n0rr_blog';

$id = $_POST['id'];
$pw = $_POST['pw'];
$nickname = '';

if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
	$ip = $_SERVER["HTTP_CLIENT_IP"];
} else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
	$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else {
	$ip = $_SERVER["REMOTE_ADDR"];
}

$id = str_replace('\'', '\\\'', $id);
$id = str_replace('\"', '\\\"', $id);
$pw = str_replace('\'', '\\\'', $pw);
$pw = str_replace('\"', '\\\"', $pw);

$checksql = "select nickname from account where id='".$id."' and pw=password('".$pw."');";
$getipsql_f = "insert into visits_member values('".$id."', '";
$getipsql_l = "', inet_aton('".$ip."'), sysdate());";

$conn = new mysqli($host, $user, $password, $dbname);

if (!($conn->connect_error)) {
	if(strlen($id) && strlen($pw)) {
		$result = $conn->query($checksql);
		if($result->num_rows != 0) {
			while($row = $result->fetch_assoc()) {
				$nickname = $row['nickname'];
			}
			session_start();
			$_SESSION['is_login'] = true;
			$_SESSION['nickname'] = $nickname;
			$conn->query($getipsql_f.$nickname.$getipsql_l);
			$conn->close();
			echo '<script>alert(\'로그인을 정상적으로 완료했습니다.\nclient_ip: '.$ip.'\');location.href=\'./\';</script>';
		} else {
			$conn->close();
			echo '<script>alert(\'아이디나 패스워드가 일치하지 않습니다.\');history.back();</script>';
		}
	} else {
		$conn->close();
		echo '<script>alert(\'양식을 모두 맞춰주세요.\');history.back();</script>';
	}
} else {
	die("Connection failed: " . $conn->connect_error);
}
?>