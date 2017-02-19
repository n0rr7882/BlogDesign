<?php
$uploaddir = './image/';
$uploadfile = $uploaddir.basename($_FILES['uploadimage']['name']);
if(move_uploaded_file($_FILES['uploadimage']['tmp_name'], $uploadfile)) {
	echo '<script>alert(\'이미지가 정상적으로 업로드 되었습니다.\n 파일명: '.$uploadfile.'\');history.back();</script>';
} else {
	echo '<script>alert(\'알 수 없는 오류로 인해 정상적으로 업로드하지 못했습니다.\');history.back();</script>';
}
?>