<?php
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include('db.php'); // Inclua o arquivo de conexão com o banco de dados

$id = $_GET['id'];

$sql = "DELETE FROM user_login WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: usuarios.php");
    exit();
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}
?>