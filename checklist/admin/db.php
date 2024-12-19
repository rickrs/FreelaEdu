<?php
$servername = "localhost"; // ou o endereço do seu servidor de banco de dados
$username = "root"; // ou seu nome de usuário do banco de dados
$password = ""; // ou sua senha do banco de dados
$dbname = "hisense_checklist"; // o nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
