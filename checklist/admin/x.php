<?php
$password = '123mudar';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>
