<?php $senha = 'admin1'; // Altere para a senha que deseja criar
$hashed_password = password_hash($senha, PASSWORD_DEFAULT);
echo $hashed_password;
?>