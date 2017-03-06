<?php
session_start();
unset($_SESSION['is_login']);
unset($_SESSION['nickname']);
echo '<script>location.href=\'../\';</script>';
?>