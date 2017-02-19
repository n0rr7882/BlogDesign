<?php
session_start();
unset($_SESSION['is_login']);
unset($_SESSION['nickname']);
echo '<script>alert(\'정상적으로 로그아웃 되었습니다.\');location.href=\'./\';</script>';
?>